<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDurationToCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'duration' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 2,
                'after'      => 'schedule_time',
                'comment'    => 'Class duration in hours (1, 2, or 3)'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'duration');
    }
}

