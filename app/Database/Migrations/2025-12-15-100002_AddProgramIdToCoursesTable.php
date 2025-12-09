<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProgramIdToCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'program_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'teacher_id',
            ],
        ]);

        // Add foreign key
        $this->forge->addForeignKey('program_id', 'programs', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('courses', 'courses_program_id_foreign');
        $this->forge->dropColumn('courses', 'program_id');
    }
}

