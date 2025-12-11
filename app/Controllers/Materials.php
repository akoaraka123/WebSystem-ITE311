<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use CodeIgniter\Controller;

class Materials extends Controller
{
    // ==============================
    // UPLOAD MATERIAL (STANDARD FORM)
    // ==============================
    public function upload($course_id)
    {
        helper(['form', 'url']);
        $session = session();
        
        // Access control: Only admin and teacher can upload materials
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('error', '❌ Please log in to upload materials.');
            return redirect()->to(base_url('login'));
        }
        
        $role = $session->get('role');
        if ($role !== 'admin' && $role !== 'teacher') {
            $session->setFlashdata('error', '❌ Access denied. Only administrators and teachers can upload materials.');
            return redirect()->to(base_url('dashboard'));
        }
        
        // For teachers: verify they can only upload to their own courses
        if ($role === 'teacher') {
            $courseModel = new \App\Models\CourseModel();
            $course = $courseModel->find($course_id);
            if (!$course || $course['teacher_id'] != $session->get('userID')) {
                $session->setFlashdata('error', '❌ Access denied. You can only upload materials to your own courses.');
                return redirect()->to(base_url('dashboard'));
            }
        }
        // Admins can upload to any course
        
        $materialModel = new MaterialModel();
        
        // Get course to find semester_id
        $courseModel = new \App\Models\CourseModel();
        $course = $courseModel->find($course_id);
        
        // Get terms for this course's semester
        $terms = [];
        if ($course && !empty($course['semester_id'])) {
            $termModel = new \App\Models\TermModel();
            $terms = $termModel->getTermsBySemester($course['semester_id']);
        }
        
        // If no terms found, use default terms (fallback)
        if (empty($terms)) {
            $terms = [
                ['id' => 1, 'term_name' => 'Prelim', 'term_order' => 1],
                ['id' => 2, 'term_name' => 'Midterm', 'term_order' => 2],
                ['id' => 3, 'term_name' => 'Finals', 'term_order' => 3]
            ];
        }

        // Check for POST request (case-insensitive)
        if (strtolower($this->request->getMethod()) === 'post') {
            $file = $this->request->getFile('material');

            
            if (!$file || !$file->isValid()) {
                $error = $file ? $file->getErrorString() : 'No file selected';
                $session->setFlashdata('error', "❌ Upload failed: $error");
                return redirect()->back()->withInput();
            }

            $allowedTypes = [
                'pdf', 'ppt', 'pptx', 'doc', 'docx'
            ];
            $ext = strtolower($file->getClientExtension());
            if (!in_array($ext, $allowedTypes)) {
                $session->setFlashdata('error', '❌ Invalid file type. Allowed: ' . implode(', ', $allowedTypes));
                return redirect()->back()->withInput();
            }

            // Validate term_id is required
            $term_id = $this->request->getPost('term_id');
            if (empty($term_id) || $term_id === '' || $term_id === '0') {
                $session->setFlashdata('error', '❌ Please select a term (PRELIM, MIDTERM, or FINAL)');
                return redirect()->back()->withInput();
            }

            $uploadPath = WRITEPATH . 'uploads/materials/';
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0777, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $uploadPath);
                    $session->setFlashdata('error', '❌ Failed to create upload directory. Please contact administrator.');
                    return redirect()->back()->withInput();
                }
            }
            
            // Check if directory is writable
            if (!is_writable($uploadPath)) {
                log_message('error', 'Upload directory is not writable: ' . $uploadPath);
                $session->setFlashdata('error', '❌ Upload directory is not writable. Please contact administrator.');
                return redirect()->back()->withInput();
            }

            $originalName = $file->getClientName();
            $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
            $newName = time() . '_' . $safeName;

            try {
                if ($file->move($uploadPath, $newName)) {
                    
                    // Get term_id from request - REQUIRED
                    $term_id = $this->request->getPost('term_id');
                    if (empty($term_id) || $term_id === '' || $term_id === '0') {
                        // Delete the uploaded file since validation failed
                        @unlink($uploadPath . $newName);
                        log_message('error', 'Term validation failed - deleting uploaded file');
                        $session->setFlashdata('error', '❌ Please select a term (PRELIM, MIDTERM, or FINAL)');
                        return redirect()->back()->withInput();
                    }
                    $term_id = (int)$term_id;
                    
                    // Insert material record
                    $insertResult = $materialModel->insertMaterial([
                        'course_id' => $course_id,
                        'term_id' => $term_id,
                        'file_name' => $originalName,
                        'file_path' => 'uploads/materials/' . $newName,
                        'created_at'=> date('Y-m-d H:i:s'),
                    ]);
                    
                    if (!$insertResult) {
                        // Delete file if database insert failed
                        @unlink($uploadPath . $newName);
                        log_message('error', 'Database insert failed for material');
                        $session->setFlashdata('error', '❌ Failed to save material record. Please try again.');
                        return redirect()->back()->withInput();
                    }
                    
                    // Create notifications for enrolled students
                    try {
                        $notif = new \App\Models\NotificationModel();
                        $enrollmentModel = new \App\Models\EnrollmentModel();
                        $courseModel = new \App\Models\CourseModel();
                        
                        // Get course details
                        $course = $courseModel->find($course_id);
                        if ($course) {
                            // Get all enrolled students
                            $enrolledStudents = $enrollmentModel->where('course_id', $course_id)->findAll();
                            
                            // Notify each enrolled student
                            foreach ($enrolledStudents as $enrollment) {
                                $notif->add($enrollment['user_id'], 
                                    'New material uploaded: ' . $originalName . ' in ' . $course['title']);
                            }
                        }
                    } catch (\Exception $notifError) {
                        // Don't fail upload if notification fails
                        log_message('error', 'Notification error: ' . $notifError->getMessage());
                    }
                    
                    $session->setFlashdata('success', '✅ Material uploaded successfully!');
                    return redirect()->to(base_url('dashboard'));
                } else {
                    $errorMsg = $file->getErrorString();
                    log_message('error', 'File move failed: ' . $errorMsg);
                    $session->setFlashdata('error', '❌ Upload failed: ' . $errorMsg);
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                log_message('error', 'Upload exception: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
                $session->setFlashdata('error', '❌ Upload error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        }

        return view('materials/upload', [
            'course_id' => $course_id,
            'terms' => $terms
        ]);
    }

    // ==============================
    // AJAX UPLOAD MATERIAL
    // ==============================
    public function upload_ajax($course_id)
    {
        helper(['form', 'url']);
        $session = session();
        
        $response = ['success' => false, 'message' => '', 'csrf_hash' => csrf_hash()];
        
        // Access control: Only admin and teacher can upload materials
        if (!$session->get('isLoggedIn')) {
            $response['message'] = 'Please log in to upload materials.';
            return $this->response->setJSON($response);
        }
        
        $role = $session->get('role');
        if ($role !== 'admin' && $role !== 'teacher') {
            $response['message'] = 'Access denied. Only administrators and teachers can upload materials.';
            return $this->response->setJSON($response);
        }
        
        // For teachers: verify they can only upload to their own courses
        if ($role === 'teacher') {
            $courseModel = new \App\Models\CourseModel();
            $course = $courseModel->find($course_id);
            if (!$course || $course['teacher_id'] != $session->get('userID')) {
                $response['message'] = 'Access denied. You can only upload materials to your own courses.';
                return $this->response->setJSON($response);
            }
        }
        // Admins can upload to any course
        
        $materialModel = new MaterialModel();

        // Process as long as a file is provided, regardless of request method quirks
        $file = $this->request->getFile('material');

        if (!$file || !$file->isValid()) {
            $error = $file ? $file->getErrorString() . ' (' . $file->getError() . ')' : 'No file selected';
            $response['message'] = $error;
            $response['csrf_hash'] = csrf_hash();
            log_message('error', 'File validation failed: ' . $error);
            return $this->response->setJSON($response);
        }

        $allowedTypes = ['pdf', 'ppt', 'pptx', 'doc', 'docx'];
        $ext = strtolower($file->getClientExtension());

        if (!in_array($ext, $allowedTypes)) {
            $response['message'] = 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes);
            $response['csrf_hash'] = csrf_hash();
            return $this->response->setJSON($response);
        }

        // Validate term_id is required
        $term_id = $this->request->getPost('term_id');
        if (empty($term_id) || $term_id === '' || $term_id === '0') {
            $response['message'] = 'Please select a term (PRELIM, MIDTERM, or FINAL)';
            $response['csrf_hash'] = csrf_hash();
            return $this->response->setJSON($response);
        }

        $uploadPath = WRITEPATH . 'uploads/materials/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $originalName = $file->getClientName();
        $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
        $newName = time() . '_' . $safeName;

            try {
                if ($file->move($uploadPath, $newName)) {
                    
                    // Get term_id from request - REQUIRED
                    $term_id = $this->request->getPost('term_id');
                    if (empty($term_id) || $term_id === '' || $term_id === '0') {
                        // Delete the uploaded file since validation failed
                        @unlink($uploadPath . $newName);
                        $response['message'] = 'Please select a term (PRELIM, MIDTERM, or FINAL)';
                        $response['csrf_hash'] = csrf_hash();
                        return $this->response->setJSON($response);
                    }
                    $term_id = (int)$term_id;
                    
                    $insertID = $materialModel->insertMaterial([
                        'course_id' => $course_id,
                        'term_id' => $term_id,
                        'file_name' => $originalName,
                        'file_path' => 'uploads/materials/' . $newName,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                // Create notifications for enrolled students
                $notif = new \App\Models\NotificationModel();
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $courseModel = new \App\Models\CourseModel();
                
                // Get course details
                $course = $courseModel->find($course_id);
                if ($course) {
                    // Get all enrolled students
                    $enrolledStudents = $enrollmentModel->where('course_id', $course_id)->findAll();
                    
                    // Notify each enrolled student
                    foreach ($enrolledStudents as $enrollment) {
                        $notif->add($enrollment['user_id'], 
                            'New material uploaded: ' . $originalName . ' in ' . $course['title']);
                    }
                }

                $response['success'] = true;
                $response['id'] = $insertID;
                $response['file_name'] = $originalName;
                $response['csrf_hash'] = csrf_hash();
                return $this->response->setJSON($response);
            } else {
                $error = $file->getErrorString();
                log_message('error', 'File move failed: ' . $error);
                $response['message'] = $error;
                $response['csrf_hash'] = csrf_hash();
                return $this->response->setJSON($response);
            }
        } catch (\Exception $e) {
            log_message('error', 'Upload exception: ' . $e->getMessage());
            $response['message'] = 'Upload error: ' . $e->getMessage();
            $response['csrf_hash'] = csrf_hash();
            return $this->response->setJSON($response);
        }
    }

    // ==============================
    // DOWNLOAD MATERIAL
    // ==============================
    public function download($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('error', '❌ Please log in to download materials.');
            return redirect()->to(base_url('login'));
        }

        $materialModel = new MaterialModel();
        $material = $materialModel->find($id);

        if (!$material) {
            $session->setFlashdata('error', '❌ Material not found.');
            return redirect()->back();
        }

        // Construct file path - files are stored in writable/uploads/materials/
        $filePath = WRITEPATH . ($material['file_path'] ?? '');
        
        // Verify file exists
        if (!is_file($filePath)) {
            $session->setFlashdata('error', '❌ File not found or already deleted.');
            return redirect()->back();
        }

        // Access control based on role
        $role = $session->get('role');
        $userID = $session->get('userID');
        $courseModel = new \App\Models\CourseModel();
        $enrollmentModel = new \App\Models\EnrollmentModel();

        if ($role === 'student') {
            // Students can only download if they are enrolled and approved
            $enrollment = $enrollmentModel->where('user_id', $userID)
                                         ->where('course_id', $material['course_id'])
                                         ->where('status', 'accepted')
                                         ->where('teacher_approved', 1)
                                         ->first();
            
            if (!$enrollment) {
                $session->setFlashdata('error', '❌ Access denied. You are not enrolled in this course or your enrollment is not approved.');
                return redirect()->back();
            }
        } elseif ($role === 'teacher') {
            // Teachers can only download materials from their own courses
            $course = $courseModel->find($material['course_id']);
            if (!$course || $course['teacher_id'] != $userID) {
                $session->setFlashdata('error', '❌ Access denied. This material belongs to a course you are not assigned to.');
                return redirect()->back();
            }
        } elseif ($role === 'admin') {
            // Admins can download any material
            // No additional check needed
        } else {
            $session->setFlashdata('error', '❌ Access denied. Invalid user role.');
            return redirect()->back();
        }

        // Set proper filename for download
        $originalFileName = $material['file_name'] ?? 'material_' . $id;
        
        return $this->response->download($filePath, $originalFileName);
    }

    // ==============================
    // DELETE MATERIAL
    // ==============================
    public function delete($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please log in to delete materials.'
                ]);
            }
            return redirect()->to(base_url('login'));
        }

        $materialModel = new MaterialModel();
        $material = $materialModel->find($id);

        if ($material) {
            // Access control - only teacher of the course or admin can delete
            $role = $session->get('role');
            $userID = $session->get('userID');
            
            if ($role === 'teacher') {
                $courseModel = new \App\Models\CourseModel();
                $course = $courseModel->find($material['course_id']);
                if (!$course || $course['teacher_id'] != $userID) {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Access denied. You can only delete materials from your own courses.'
                        ]);
                    }
                    $session->setFlashdata('error', '❌ Access denied. You can only delete materials from your own courses.');
                    return redirect()->back();
                }
            } elseif ($role !== 'admin') {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Access denied. Only teachers and admins can delete materials.'
                    ]);
                }
                $session->setFlashdata('error', '❌ Access denied. Only teachers and admins can delete materials.');
                return redirect()->back();
            }

            // Delete the physical file - files are stored in writable/uploads/materials/
            $filePath = WRITEPATH . $material['file_path'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            
            $materialModel->delete($id);
            
            // Always return JSON for AJAX requests, check multiple ways
            $isAjax = $this->request->isAJAX() || 
                     $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest' ||
                     strpos($this->request->getHeaderLine('Accept'), 'application/json') !== false;
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Material deleted successfully.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            
            session()->setFlashdata('success', '🗑️ Material deleted successfully.');
            return redirect()->back();
        } else {
            // Always return JSON for AJAX requests
            $isAjax = $this->request->isAJAX() || 
                     $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest' ||
                     strpos($this->request->getHeaderLine('Accept'), 'application/json') !== false;
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Material not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            
            session()->setFlashdata('error', '❌ Material not found.');
            return redirect()->back();
        }
    }

    // ==============================
    // GET MATERIALS (AJAX)
    // ==============================
    public function getMaterials($course_id)
    {
        $materialModel = new MaterialModel();
        $materials = $materialModel->where('course_id', $course_id)->findAll();

        $data = array_map(static function($mat){
            return [
                'id' => $mat['id'],
                'file_name' => $mat['file_name'],
                'download_url' => base_url('materials/download/' . $mat['id']),
            ];
        }, $materials);

        return $this->response->setJSON($data);
    }
}
