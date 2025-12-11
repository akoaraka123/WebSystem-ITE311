<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduleDayFieldsToCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'schedule_day_start' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'schedule_date',
                'comment'    => 'Start day of the week (e.g., Monday, Mon, M)'
            ],
            'schedule_day_end' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'schedule_day_start',
                'comment'    => 'End day of the week (e.g., Friday, Fri, F)'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', ['schedule_day_start', 'schedule_day_end']);
    }
}
