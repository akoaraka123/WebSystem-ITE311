<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Course extends BaseController
{
    public function enroll()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $userID = $session->get('userID');
        $courseID = (int) $this->request->getPost('course_id');

        if ($courseID <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course.', 'csrf_hash' => csrf_hash()]);
        }

        $courseModel = new CourseModel();
        $course = $courseModel->find($courseID);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found.', 'csrf_hash' => csrf_hash()]);
        }

        $enrollModel = new EnrollmentModel();
        if ($enrollModel->isAlreadyEnrolled($userID, $courseID)) {
            return $this->response->setJSON(['success' => false, 'message' => 'You are already enrolled in this course.', 'csrf_hash' => csrf_hash()]);
        }

        $enrollModel->enrollUser([
            'user_id' => $userID,
            'course_id' => $courseID,
            'enrollment_date' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'You have successfully enrolled in the course!', 'csrf_hash' => csrf_hash()]);
    }
}
