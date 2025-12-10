<?php

namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'id';
    protected $allowedFields = ['acad_year_id', 'semester_number', 'name', 'start_date', 'end_date', 'is_active', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get semesters by academic year
     */
    public function getSemestersByAcademicYear($acadYearId)
    {
        return $this->where('acad_year_id', $acadYearId)
                    ->where('is_active', 1)
                    ->orderBy('semester_number', 'ASC')
                    ->findAll();
    }

    /**
     * Get all active semesters
     */
    public function getActiveSemesters()
    {
        return $this->where('is_active', 1)
                    ->orderBy('acad_year_id', 'DESC')
                    ->orderBy('semester_number', 'ASC')
                    ->findAll();
    }

    /**
     * Get semester by ID
     */
    public function getSemester($id)
    {
        return $this->find($id);
    }

    /**
     * Get semester with academic year info
     */
    public function getSemesterWithAcademicYear($id)
    {
        return $this->select('semesters.*, academic_years.display_name as acad_year_name, academic_years.year_start, academic_years.year_end')
                    ->join('academic_years', 'academic_years.id = semesters.acad_year_id')
                    ->where('semesters.id', $id)
                    ->first();
    }
}

