<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Course extends BaseController
{
    public function enroll()
    {
        $session = session();
        $user_id = $session->get('user_id');

        if (!$user_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please log in first.']);
        }

        $course_id = $this->request->getPost('course_id');
        $enrollModel = new EnrollmentModel();

        // Check if already enrolled
        if ($enrollModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'You are already enrolled in this course.']);
        }

        $data = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        $enrollModel->enrollUser($data);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Enrollment successful!']);
    }
}
