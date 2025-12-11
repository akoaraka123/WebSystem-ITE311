<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentProgramsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Student user ID'
            ],
            'program_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Program ID'
            ],
            'acad_year_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Academic year when enrolled'
            ],
            'enrollment_date' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => 'Date when student was enrolled in program'
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
                'comment'    => 'active, completed, transferred, dropped'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('program_id');
        $this->forge->addKey('acad_year_id');
        $this->forge->addKey('status');
        
        // Add foreign keys
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('program_id', 'programs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('acad_year_id', 'academic_years', 'id', 'SET NULL', 'CASCADE');
        
        // Unique constraint: one active program enrollment per student per academic year
        $this->forge->addUniqueKey(['user_id', 'program_id', 'acad_year_id']);
        
        $this->forge->createTable('student_programs', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('student_programs', true);
    }
}

