<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeTeacherIdNullableInCoursesTable extends Migration
{
    public function up()
    {
        // Drop the foreign key first
        $this->forge->dropForeignKey('courses', 'courses_teacher_id_foreign');
        
        // Modify teacher_id to allow NULL
        $this->forge->modifyColumn('courses', [
            'teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Allow NULL for pending assignments
            ],
        ]);
        
        // Re-add the foreign key with SET NULL on delete (since teacher_id can be null)
        $this->forge->addForeignKey('teacher_id', 'users', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        // Drop the foreign key
        $this->forge->dropForeignKey('courses', 'courses_teacher_id_foreign');
        
        // Make teacher_id NOT NULL again (but first need to ensure no NULL values exist)
        // Note: This might fail if there are courses with NULL teacher_id
        $this->forge->modifyColumn('courses', [
            'teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
        
        // Re-add the foreign key
        $this->forge->addForeignKey('teacher_id', 'users', 'id', 'CASCADE', 'CASCADE');
    }
}
