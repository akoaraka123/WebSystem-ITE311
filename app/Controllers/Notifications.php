<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    // Guide: GET /notifications -> get()
    public function get()
    {
        return $this->index();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $userID = (int) $session->get('userID');
        $limit  = (int) ($this->request->getGet('limit') ?? 10);

        $model = new NotificationModel();
        $items = $model->getLatest($userID, $limit);
        
        // Auto-clean stale enrollment-related notifications
        $role = $session->get('role');
        $filtered = [];
        if (!empty($items)) {
            $courseModel = new \App\Models\CourseModel();
            $enrollModel = new \App\Models\EnrollmentModel();
            foreach ($items as $it) {
                $msg = $it['message'] ?? '';
                $keep = true;
                // Extract course title from known patterns
                if (stripos($msg, 'You enrolled in:') === 0) {
                    $courseTitle = trim(substr($msg, strlen('You enrolled in:')));
                    $course = $courseModel->where('title', $courseTitle)->first();
                    if (!$course || !$enrollModel->isAlreadyEnrolled($userID, (int)$course['id'])) {
                        // student no longer enrolled -> delete notif
                        $model->delete((int)$it['id']);
                        $keep = false;
                    }
                } elseif (stripos($msg, 'A student enrolled in your course:') === 0) {
                    $courseTitle = trim(substr($msg, strlen('A student enrolled in your course:')));
                    $course = $courseModel->where('title', $courseTitle)->first();
                    if (!$course) {
                        $model->delete((int)$it['id']);
                        $keep = false;
                    } else {
                        // If no enrollments remain for this course, remove the teacher notif
                        $any = $enrollModel->where('course_id', (int)$course['id'])->countAllResults() > 0;
                        if (!$any) {
                            $model->delete((int)$it['id']);
                            $keep = false;
                        }
                    }
                }

                if ($keep) $filtered[] = $it;
            }
        }

        $unread = $model->getUnreadCount($userID);

        return $this->response->setJSON([
            'success'    => true,
            'unread'     => $unread,
            'items'      => $filtered,
            'csrf_hash'  => csrf_hash(),
        ]);
    }

    public function markRead()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $userID = (int) $session->get('userID');
        $model = new NotificationModel();
        $model->markAllRead($userID);

        return $this->response->setJSON([
            'success'   => true,
            'csrf_hash' => csrf_hash(),
        ]);
    }

    // Guide: POST /notifications/mark_read/{id}
    public function mark_as_read($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $notifId = (int) $id;
        if ($notifId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid notification', 'csrf_hash' => csrf_hash()]);
        }

        $model = new NotificationModel();
        $ok = $model->markAsRead($notifId);

        return $this->response->setJSON([
            'success'   => (bool) $ok,
            'csrf_hash' => csrf_hash(),
        ]);
    }

    public function resolve($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $notifId = (int) $id;
        if ($notifId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid notification', 'csrf_hash' => csrf_hash()]);
        }

        $model = new NotificationModel();
        $notif = $model->find($notifId);
        if (!$notif || (int) ($notif['user_id'] ?? 0) !== (int) $session->get('userID')) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Notification not found.', 'csrf_hash' => csrf_hash()]);
        }

        $course = $this->resolveCourseFromMessage((string) ($notif['message'] ?? ''));
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No related course found for this notification.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $model->markAsRead($notifId);

        return $this->response->setJSON([
            'success' => true,
            'url' => base_url('course/' . $course['id']),
            'csrf_hash' => csrf_hash(),
        ]);
    }

    private function resolveCourseFromMessage(string $message): ?array
    {
        $message = trim($message);
        if ($message === '') {
            return null;
        }

        $courseTitle = null;
        $prefixes = [
            'You enrolled in:' => strlen('You enrolled in:'),
            'A student enrolled in your course:' => strlen('A student enrolled in your course:'),
        ];

        foreach ($prefixes as $prefix => $length) {
            if (stripos($message, $prefix) === 0) {
                $courseTitle = trim(substr($message, $length));
                break;
            }
        }

        if (!$courseTitle) {
            $needle = ' in ';
            $pos = strripos($message, $needle);
            if ($pos !== false) {
                $courseTitle = trim(substr($message, $pos + strlen($needle)));
            }
        }

        if (!$courseTitle) {
            return null;
        }

        $courseModel = new \App\Models\CourseModel();
        $course = $courseModel->where('title', $courseTitle)->first();
        if (!$course) {
            $course = $courseModel->like('title', $courseTitle)->first();
        }

        return $course ?: null;
    }

    // Optional: helper to create a test notification
    public function add()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }
        $userID = (int) $session->get('userID');
        $msg = $this->request->getPost('message') ?? 'New notification';
        $model = new NotificationModel();
        $model->add($userID, $msg);
        return $this->response->setJSON(['success' => true, 'csrf_hash' => csrf_hash()]);
    }
}
