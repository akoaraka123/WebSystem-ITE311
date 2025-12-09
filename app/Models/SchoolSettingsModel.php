<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolSettingsModel extends Model
{
    protected $table = 'school_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['school_year', 'semester', 'start_date', 'end_date', 'is_active', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get active school settings
     */
    public function getActiveSettings()
    {
        return $this->where('is_active', 1)->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Get all school settings
     */
    public function getAllSettings()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Deactivate all other settings when setting a new one as active
     */
    public function deactivateAll()
    {
        return $this->set('is_active', 0)->update();
    }
}

