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
}

