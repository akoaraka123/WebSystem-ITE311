<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'term_id', 'file_name', 'file_path', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    // 🔹 Get all materials for a specific course
    public function getMaterialsByCourse($course_id)
    {
        $materials = $this->where('course_id', $course_id)->findAll();
        return $materials ? $materials : [];
    }

    public function insertMaterial(array $data)
    {
        return $this->insert($data);
    }
}
