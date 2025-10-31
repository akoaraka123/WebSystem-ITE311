<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table         = 'notifications';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'message', 'is_read', 'created_at'];

    public function getUnreadCount(int $userId): int
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    public function getLatest(int $userId, int $limit = 10): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    // Lab 8 guide: latest notifications (default 5)
    public function getNotificationsForUser(int $userId, int $limit = 5): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    public function markAllRead(int $userId): bool
    {
        return (bool) $this->where('user_id', $userId)
            ->set('is_read', 1)
            ->update();
    }

    public function add(int $userId, string $message)
    {
        return $this->insert([
            'user_id'   => $userId,
            'message'   => $message,
            'is_read'   => 0,
            'created_at'=> date('Y-m-d H:i:s')
        ]);
    }

    // Lab 8 guide: mark a specific notification as read
    public function markAsRead(int $id): bool
    {
        return (bool) $this->update($id, ['is_read' => 1]);
    }

    // Delete notifications for a user where message LIKE a pattern (e.g., "%Course Title%")
    public function deleteByUserAndMessageLike(int $userId, string $pattern): bool
    {
        return (bool) $this->where('user_id', $userId)
            ->like('message', $pattern, 'both')
            ->delete();
    }

    // Convenience: clear enrollment-related notifications for both student and teacher
    public function clearEnrollmentNotifs(int $studentId, ?int $teacherId, string $courseTitle): void
    {
        $this->deleteByUserAndMessageLike($studentId, $courseTitle);
        if (!empty($teacherId)) {
            $this->deleteByUserAndMessageLike((int)$teacherId, $courseTitle);
        }
    }
}
