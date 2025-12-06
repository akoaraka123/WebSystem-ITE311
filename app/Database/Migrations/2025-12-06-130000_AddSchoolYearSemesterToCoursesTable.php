<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSchoolYearSemesterToCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'school_year' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'description'
            ],
            'semester' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'school_year'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', ['school_year', 'semester']);
    }
}
