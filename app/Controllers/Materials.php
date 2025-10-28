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
        $materialModel = new MaterialModel();

        if ($this->request->getMethod() === 'post') {
            $file = $this->request->getFile('material');

            
            if (!$file || !$file->isValid()) {
                $error = $file ? $file->getErrorString() : 'No file selected';
                $session->setFlashdata('error', "❌ Upload failed: $error");
                return redirect()->back()->withInput();
            }

            $allowedTypes = [
                'pdf', 'doc', 'docx', 'ppt', 'pptx',
                'xls', 'xlsx', 'zip', 'rar',
                'png', 'jpg', 'jpeg', 'gif', 'txt'
            ];
            $ext = strtolower($file->getClientExtension());
            if (!in_array($ext, $allowedTypes)) {
                $session->setFlashdata('error', '❌ Invalid file type. Allowed: ' . implode(', ', $allowedTypes));
                return redirect()->back()->withInput();
            }

            $uploadPath = ROOTPATH . 'public/uploads/materials/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

            $originalName = $file->getClientName();
            $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
            $newName = time() . '_' . $safeName;

            try {
                if ($file->move($uploadPath, $newName)) {
                    $materialModel->insertMaterial([
                        'course_id' => $course_id,
                        'file_name' => $originalName,
                        'file_path' => 'uploads/materials/' . $newName,
                        'created_at'=> date('Y-m-d H:i:s'),
                    ]);
                    $session->setFlashdata('success', '✅ Material uploaded successfully!');
                    return redirect()->to(base_url('dashboard'));
                } else {
                    $session->setFlashdata('error', '❌ Upload failed: ' . $file->getErrorString());
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                $session->setFlashdata('error', '❌ Upload error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        }

        return view('materials/upload', ['course_id' => $course_id]);
    }

    // ==============================
    // AJAX UPLOAD MATERIAL
    // ==============================
    public function upload_ajax($course_id)
    {
        helper(['form', 'url']);
        $materialModel = new MaterialModel();

        $response = ['success' => false, 'message' => '', 'csrf_hash' => csrf_hash()];

        // Process as long as a file is provided, regardless of request method quirks
        $file = $this->request->getFile('material');

        if (!$file || !$file->isValid()) {
            $response['message'] = $file ? $file->getErrorString() : 'No file selected';
            $response['csrf_hash'] = csrf_hash();
            return $this->response->setJSON($response);
        }

        $allowedTypes = ['pdf','doc','docx','ppt','pptx','xls','xlsx','zip','rar','png','jpg','jpeg','gif','txt'];
        $ext = strtolower($file->getClientExtension());

        if (!in_array($ext, $allowedTypes)) {
            $response['message'] = 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes);
            $response['csrf_hash'] = csrf_hash();
            return $this->response->setJSON($response);
        }

        $uploadPath = ROOTPATH . 'public/uploads/materials/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        $originalName = $file->getClientName();
        $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
        $newName = time() . '_' . $safeName;

        try {
            if ($file->move($uploadPath, $newName)) {
                $insertID = $materialModel->insertMaterial([
                    'course_id' => $course_id,
                    'file_name' => $originalName,
                    'file_path' => 'uploads/materials/' . $newName,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                $response['success'] = true;
                $response['id'] = $insertID;
                $response['file_name'] = $originalName;
                $response['csrf_hash'] = csrf_hash();
                return $this->response->setJSON($response);
            } else {
                $response['message'] = $file->getErrorString();
                $response['csrf_hash'] = csrf_hash();
                return $this->response->setJSON($response);
            }
        } catch (\Exception $e) {
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
            return redirect()->to(base_url('login'));
        }

        $materialModel = new MaterialModel();
        $material = $materialModel->find($id);

        $filePath = ROOTPATH . 'public/' . ($material['file_path'] ?? '');
        if (!$material || !is_file($filePath)) {
            $session->setFlashdata('error', '❌ File not found or already deleted.');
            return redirect()->back();
        }

        $role = $session->get('role');
        if ($role === 'student') {
            $userID = $session->get('userID');
            $enrollmentModel = new \App\Models\EnrollmentModel();
            if (!$enrollmentModel->isAlreadyEnrolled($userID, $material['course_id'])) {
                $session->setFlashdata('error', '❌ Access denied. You are not enrolled in this course.');
                return redirect()->back();
            }
        }

        return $this->response->download($filePath, null);
    }

    // ==============================
    // DELETE MATERIAL
    // ==============================
    public function delete($id)
    {
        $materialModel = new MaterialModel();
        $material = $materialModel->find($id);

        if ($material) {
            $filePath = ROOTPATH . 'public/' . $material['file_path'];
            if (file_exists($filePath)) unlink($filePath);
            
            $materialModel->delete($id);
            session()->setFlashdata('success', '🗑️ Material deleted successfully.');
        } else {
            session()->setFlashdata('error', '❌ Material not found.');
        }

        return redirect()->back();
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
