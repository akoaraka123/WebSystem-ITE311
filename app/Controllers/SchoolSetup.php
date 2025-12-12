<?php

namespace App\Controllers;

use App\Models\SchoolSettingsModel;
use App\Models\ProgramModel;
use App\Models\AcademicYearModel;
use App\Models\SemesterModel;
use App\Models\TermModel;
use App\Controllers\BaseController;

class SchoolSetup extends BaseController
{
    protected $schoolSettingsModel;
    protected $programModel;
    protected $academicYearModel;
    protected $semesterModel;
    protected $termModel;

    public function __construct()
    {
        $this->schoolSettingsModel = new SchoolSettingsModel();
        $this->programModel = new ProgramModel();
        $this->academicYearModel = new AcademicYearModel();
        $this->semesterModel = new SemesterModel();
        $this->termModel = new TermModel();
    }

    /**
     * Display school setup page (Admin only)
     */
    public function index()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('dashboard'));
        }

        // Get semesters with academic year info
        $semesters = $this->semesterModel->select('semesters.*, academic_years.display_name as acad_year_name')
                                         ->join('academic_years', 'academic_years.id = semesters.acad_year_id')
                                         ->where('semesters.is_active', 1)
                                         ->orderBy('academic_years.year_start', 'DESC')
                                         ->orderBy('semesters.semester_number', 'ASC')
                                         ->findAll();

        $data = [
            'title' => 'School Setup - LMS',
            'user' => $session->get(),
            'activeSettings' => $this->schoolSettingsModel->getActiveSettings(),
            'allSettings' => $this->schoolSettingsModel->getAllSettings(),
            'programs' => $this->programModel->getAllPrograms(),
            'academicYears' => $this->academicYearModel->getAllAcademicYears(),
            'semesters' => $semesters
        ];

        return view('admin/school_setup', $data);
    }

    /**
     * Save or update school settings
     */
    public function saveSettings()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $rules = [
            'school_year' => 'required|min_length[4]|max_length[20]',
            'semester' => 'required|in_list[1st Semester,2nd Semester,Summer]',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $this->validator->getErrors(), 'csrf_hash' => csrf_hash()]);
        }

        // Validate dates
        $startDate = strtotime($this->request->getPost('start_date'));
        $endDate = strtotime($this->request->getPost('end_date'));

        if ($startDate >= $endDate) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'End date must be after start date', 'csrf_hash' => csrf_hash()]);
        }

        try {
            // If setting as active, deactivate all others first
            $isActive = $this->request->getPost('is_active') == '1';
            if ($isActive) {
                $this->schoolSettingsModel->deactivateAll();
            }

            $data = [
                'school_year' => $this->request->getPost('school_year'),
                'semester' => $this->request->getPost('semester'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'is_active' => $isActive ? 1 : 0
            ];

            $settingsId = $this->request->getPost('settings_id');
            if ($settingsId) {
                // Update existing
                $this->schoolSettingsModel->update($settingsId, $data);
                $message = 'School settings updated successfully!';
            } else {
                // Create new
                $this->schoolSettingsModel->insert($data);
                $message = 'School settings created successfully!';
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to save settings: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Create or update program
     */
    public function saveProgram()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $rules = [
            'code' => 'required|min_length[2]|max_length[20]',
            'name' => 'required|min_length[3]|max_length[200]',
            'description' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $this->validator->getErrors(), 'csrf_hash' => csrf_hash()]);
        }

        try {
            $data = [
                'code' => strtoupper(trim($this->request->getPost('code'))),
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'is_active' => $this->request->getPost('is_active') == '1' ? 1 : 0
            ];

            $programId = $this->request->getPost('program_id');
            
            // Check if code already exists (for new programs)
            if (!$programId) {
                $existing = $this->programModel->getByCode($data['code']);
                if ($existing) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['success' => false, 'message' => 'Program code already exists', 'csrf_hash' => csrf_hash()]);
                }
            } else {
                // For updates, check if code exists for other programs
                $existing = $this->programModel->getByCode($data['code']);
                if ($existing && $existing['id'] != $programId) {
                    return $this->response->setStatusCode(400)
                        ->setJSON(['success' => false, 'message' => 'Program code already exists', 'csrf_hash' => csrf_hash()]);
                }
            }

            if ($programId) {
                // Update existing
                $this->programModel->update($programId, $data);
                $message = 'Program updated successfully!';
            } else {
                // Create new
                $this->programModel->insert($data);
                $message = 'Program created successfully!';
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to save program: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Delete program
     */
    public function deleteProgram($id)
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $program = $this->programModel->find($id);
            if (!$program) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['success' => false, 'message' => 'Program not found', 'csrf_hash' => csrf_hash()]);
            }

            // Check if program has courses
            $courseModel = new \App\Models\CourseModel();
            $coursesWithProgram = $courseModel->where('program_id', $id)->countAllResults();
            
            if ($coursesWithProgram > 0) {
                return $this->response->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Cannot delete program. It has ' . $coursesWithProgram . ' course(s) assigned to it.',
                        'csrf_hash' => csrf_hash()
                    ]);
            }

            $this->programModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Program deleted successfully!',
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete program: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Get program details (for editing)
     */
    public function getProgram($id)
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $program = $this->programModel->find($id);
        if (!$program) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Program not found', 'csrf_hash' => csrf_hash()]);
        }

        return $this->response->setJSON([
            'success' => true,
            'program' => $program,
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Save or update academic year
     */
    public function saveAcademicYear()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        // Validate year inputs - allow any reasonable year values
        $yearStart = $this->request->getPost('year_start');
        $yearEnd = $this->request->getPost('year_end');
        
        // Check if values are provided and are numeric
        if (empty($yearStart) || !is_numeric($yearStart)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Year Start is required and must be a number', 'csrf_hash' => csrf_hash()]);
        }
        
        if (empty($yearEnd) || !is_numeric($yearEnd)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Year End is required and must be a number', 'csrf_hash' => csrf_hash()]);
        }
        
        $yearStart = (int) $yearStart;
        $yearEnd = (int) $yearEnd;
        
        // Validate reasonable year range (1900 to 2100)
        if ($yearStart < 1900 || $yearStart > 2100) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Year Start must be between 1900 and 2100', 'csrf_hash' => csrf_hash()]);
        }
        
        if ($yearEnd < 1900 || $yearEnd > 2100) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Year End must be between 1900 and 2100', 'csrf_hash' => csrf_hash()]);
        }
        
        // Validate that year_end is greater than year_start
        if ($yearEnd <= $yearStart) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Bawal ang year start ' . $yearStart . ' to year end ' . $yearEnd . '. Year End must be greater than Year Start.', 'csrf_hash' => csrf_hash()]);
        }

        // Auto-generate display name from year_start and year_end
        $displayName = $yearStart . '-' . $yearEnd;

        // Check for duplicate academic year (same year_start and year_end)
        $acadYearId = $this->request->getPost('acad_year_id');
        $existingAcadYear = $this->academicYearModel
            ->where('year_start', $yearStart)
            ->where('year_end', $yearEnd);
        
        // If updating, exclude the current record from duplicate check
        if ($acadYearId) {
            $existingAcadYear->where('id !=', $acadYearId);
        }
        
        $duplicate = $existingAcadYear->first();
        
        if ($duplicate) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Duplicate academic year is not allowed. An academic year ' . $displayName . ' already exists.', 'csrf_hash' => csrf_hash()]);
        }
        
        // Check for overlapping academic years
        // An overlap occurs if:
        // 1. New year_start is between existing year_start and year_end
        // 2. New year_end is between existing year_start and year_end
        // 3. New range completely contains an existing range
        // 4. Existing range completely contains the new range
        $overlappingQuery = $this->academicYearModel
            ->groupStart()
                // Case 1 & 2: New range overlaps with existing range
                ->where('year_start <=', $yearEnd)
                ->where('year_end >=', $yearStart)
            ->groupEnd();
        
        // If updating, exclude the current record from overlap check
        if ($acadYearId) {
            $overlappingQuery->where('id !=', $acadYearId);
        }
        
        $overlapping = $overlappingQuery->first();
        
        if ($overlapping) {
            $overlappingDisplayName = $overlapping['year_start'] . '-' . $overlapping['year_end'];
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Overlapping academic year is not allowed. The academic year ' . $displayName . ' overlaps with existing academic year ' . $overlappingDisplayName . '.', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $data = [
                'year_start' => $yearStart,
                'year_end' => $yearEnd,
                'display_name' => $displayName,
                'is_active' => $this->request->getPost('is_active') == '1' ? 1 : 0
            ];
            
            if ($acadYearId) {
                // Update existing - don't auto-generate semesters/terms for updates
                $this->academicYearModel->update($acadYearId, $data);
                $message = 'Academic year updated successfully!';
            } else {
                // Create new academic year
                $newAcadYearId = $this->academicYearModel->insert($data);
                
                if ($newAcadYearId) {
                    // Auto-generate 3 semesters for the new academic year
                    $semesters = [
                        ['number' => 1, 'name' => '1st Semester'],
                        ['number' => 2, 'name' => '2nd Semester'],
                        ['number' => 3, 'name' => 'Summer']
                    ];
                    
                    foreach ($semesters as $semester) {
                        $semesterData = [
                            'acad_year_id' => $newAcadYearId,
                            'semester_number' => $semester['number'],
                            'name' => $semester['name'],
                            'is_active' => 1
                        ];
                        
                        $semesterId = $this->semesterModel->insert($semesterData);
                        
                        if ($semesterId) {
                            // Auto-generate 3 terms for each semester (Prelim, Midterm, Final)
                            $terms = [
                                ['name' => 'Prelim', 'order' => 1],
                                ['name' => 'Midterm', 'order' => 2],
                                ['name' => 'Final', 'order' => 3]
                            ];
                            
                            foreach ($terms as $term) {
                                $termData = [
                                    'semester_id' => $semesterId,
                                    'term_name' => $term['name'],
                                    'term_order' => $term['order'],
                                    'is_active' => 1
                                ];
                                
                                $this->termModel->insert($termData);
                            }
                        }
                    }
                    
                    $message = 'Academic year created successfully with 3 semesters and 3 terms each!';
                } else {
                    throw new \Exception('Failed to create academic year');
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to save academic year: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Get academic year details (for editing)
     */
    public function getAcademicYear($id)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $acadYear = $this->academicYearModel->find($id);
        if (!$acadYear) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Academic year not found', 'csrf_hash' => csrf_hash()]);
        }

        return $this->response->setJSON([
            'success' => true,
            'academicYear' => $acadYear,
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Get semester details (for editing)
     */
    public function getSemester($id)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $semester = $this->semesterModel->find($id);
        if (!$semester) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Semester not found', 'csrf_hash' => csrf_hash()]);
        }

        return $this->response->setJSON([
            'success' => true,
            'semester' => $semester,
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Save or update semester
     */
    public function saveSemester()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $rules = [
            'acad_year_id' => 'required|integer',
            'semester_number' => 'required|integer|in_list[1,2,3]',
            'start_date' => 'permit_empty|valid_date',
            'end_date' => 'permit_empty|valid_date'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $this->validator->getErrors(), 'csrf_hash' => csrf_hash()]);
        }

        // Generate semester name based on semester_number
        $semesterNumber = (int) $this->request->getPost('semester_number');
        $semesterNames = [
            1 => '1st Semester',
            2 => '2nd Semester',
            3 => 'Summer'
        ];
        $semesterName = $semesterNames[$semesterNumber] ?? 'Unknown';

        // Validate dates if provided
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        
        if (!empty($startDate) && !empty($endDate)) {
            $startTimestamp = strtotime($startDate);
            $endTimestamp = strtotime($endDate);
            
            if ($startTimestamp >= $endTimestamp) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['success' => false, 'message' => 'End date must be after start date', 'csrf_hash' => csrf_hash()]);
            }
        }

        try {
            $data = [
                'acad_year_id' => (int) $this->request->getPost('acad_year_id'),
                'semester_number' => $semesterNumber,
                'name' => $semesterName,
                'start_date' => !empty($startDate) ? $startDate : null,
                'end_date' => !empty($endDate) ? $endDate : null,
                'is_active' => $this->request->getPost('is_active') == '1' ? 1 : 0
            ];

            $semesterId = $this->request->getPost('semester_id');
            
            if ($semesterId) {
                // Update existing
                $this->semesterModel->update($semesterId, $data);
                $message = 'Semester updated successfully!';
            } else {
                // Create new
                $this->semesterModel->insert($data);
                $message = 'Semester created successfully!';
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to save semester: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Delete semester
     */
    public function deleteSemester($id)
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $semester = $this->semesterModel->find($id);
            if (!$semester) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['success' => false, 'message' => 'Semester not found', 'csrf_hash' => csrf_hash()]);
            }

            // Check if semester has courses
            $courseModel = new \App\Models\CourseModel();
            $coursesWithSemester = $courseModel->where('semester_id', $id)->countAllResults();
            
            if ($coursesWithSemester > 0) {
                return $this->response->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Cannot delete semester. It has ' . $coursesWithSemester . ' course(s) assigned to it.',
                        'csrf_hash' => csrf_hash()
                    ]);
            }

            $this->semesterModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Semester deleted successfully!',
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete semester: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Delete academic year
     */
    public function deleteAcademicYear($id)
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $acadYear = $this->academicYearModel->find($id);
            if (!$acadYear) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['success' => false, 'message' => 'Academic year not found', 'csrf_hash' => csrf_hash()]);
            }

            // Check if academic year has semesters
            $semestersWithAcadYear = $this->semesterModel->where('acad_year_id', $id)->countAllResults();
            
            if ($semestersWithAcadYear > 0) {
                return $this->response->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Cannot delete academic year. It has ' . $semestersWithAcadYear . ' semester(s) assigned to it.',
                        'csrf_hash' => csrf_hash()
                    ]);
            }

            // Check if academic year has courses
            $courseModel = new \App\Models\CourseModel();
            $coursesWithAcadYear = $courseModel->where('acad_year_id', $id)->countAllResults();
            
            if ($coursesWithAcadYear > 0) {
                return $this->response->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Cannot delete academic year. It has ' . $coursesWithAcadYear . ' course(s) assigned to it.',
                        'csrf_hash' => csrf_hash()
                    ]);
            }

            $this->academicYearModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Academic year deleted successfully!',
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete academic year: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }
}

