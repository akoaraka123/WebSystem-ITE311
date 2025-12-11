<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduleDateStartEndToCoursesTable extends Migration
{
    public function up()
    {
        // Drop old day fields
        $this->forge->dropColumn('courses', ['schedule_day_start', 'schedule_day_end']);
        
        // Add new date fields
        $this->forge->addColumn('courses', [
            'schedule_date_start' => [
                'type'       => 'DATE',
                'null'       => true,
                'after'      => 'schedule_date',
                'comment'    => 'Start date of the schedule'
            ],
            'schedule_date_end' => [
                'type'       => 'DATE',
                'null'       => true,
                'after'      => 'schedule_date_start',
                'comment'    => 'End date of the schedule'
            ],
        ]);
    }

    public function down()
    {
        // Drop new date fields
        $this->forge->dropColumn('courses', ['schedule_date_start', 'schedule_date_end']);
        
        // Restore old day fields
        $this->forge->addColumn('courses', [
            'schedule_day_start' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'schedule_date',
            ],
            'schedule_day_end' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'schedule_day_start',
            ],
        ]);
    }
}
