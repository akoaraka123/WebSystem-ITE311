<?php

namespace App\Models;

use CodeIgniter\Model;

class TermModel extends Model
{
    protected $table = 'terms';
    protected $primaryKey = 'id';
    protected $allowedFields = ['semester_id', 'term_name', 'term_order', 'start_date', 'end_date', 'is_active', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get terms by semester
     */
    public function getTermsBySemester($semesterId)
    {
        return $this->where('semester_id', $semesterId)
                    ->where('is_active', 1)
                    ->orderBy('term_order', 'ASC')
                    ->findAll();
    }

    /**
     * Get all active terms
     */
    public function getActiveTerms()
    {
        return $this->where('is_active', 1)
                    ->orderBy('semester_id', 'ASC')
                    ->orderBy('term_order', 'ASC')
                    ->findAll();
    }

    /**
     * Get term by ID
     */
    public function getTerm($id)
    {
        return $this->find($id);
    }

    /**
     * Get term with semester and academic year info
     */
    public function getTermWithDetails($id)
    {
        return $this->select('terms.*, semesters.name as semester_name, semesters.semester_number, academic_years.display_name as acad_year_name')
                    ->join('semesters', 'semesters.id = terms.semester_id')
                    ->join('academic_years', 'academic_years.id = semesters.acad_year_id')
                    ->where('terms.id', $id)
                    ->first();
    }
}

