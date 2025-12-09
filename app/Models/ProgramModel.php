<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'name', 'description', 'is_active', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get active programs
     */
    public function getActivePrograms()
    {
        return $this->where('is_active', 1)->orderBy('code', 'ASC')->findAll();
    }

    /**
     * Get all programs
     */
    public function getAllPrograms()
    {
        return $this->orderBy('code', 'ASC')->findAll();
    }

    /**
     * Get program by code
     */
    public function getByCode($code)
    {
        return $this->where('code', $code)->first();
    }
}

