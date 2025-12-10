<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateScheduleTimeToStartEnd extends Migration
{
    public function up()
    {
        // Add new columns for start and end time
        $this->forge->addColumn('courses', [
            'schedule_time_start' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'schedule_time',
                'comment' => 'Class start time (e.g., 11:00:00)'
            ],
            'schedule_time_end' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'schedule_time_start',
                'comment' => 'Class end time (e.g., 12:00:00)'
            ],
        ]);
        
        // Migrate existing schedule_time to schedule_time_start if it exists
        // Note: schedule_time will be kept for backward compatibility, can be removed later
    }

    public function down()
    {
        $this->forge->dropColumn('courses', ['schedule_time_start', 'schedule_time_end']);
    }
}

