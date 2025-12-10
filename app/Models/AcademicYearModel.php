<?php

namespace App\Models;

use CodeIgniter\Model;

class AcademicYearModel extends Model
{
    protected $table = 'academic_years';
    protected $primaryKey = 'id';
    protected $allowedFields = ['year_start', 'year_end', 'display_name', 'is_active', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all active academic years
     */
    public function getActiveAcademicYears()
    {
        return $this->where('is_active', 1)
                    ->orderBy('year_start', 'DESC')
                    ->findAll();
    }

    /**
     * Get all academic years (including inactive)
     */
    public function getAllAcademicYears()
    {
        return $this->orderBy('year_start', 'DESC')->findAll();
    }

    /**
     * Get academic year by ID
     */
    public function getAcademicYear($id)
    {
        return $this->find($id);
    }
}

