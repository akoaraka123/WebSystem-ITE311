<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeacherAssignmentApprovalToCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'pending_teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Teacher ID pending approval'
            ],
            'teacher_assignment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => null,
                'comment'    => 'pending, accepted, rejected'
            ],
            'teacher_assignment_requested_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When teacher assignment was requested'
            ],
        ]);

        // Add index for pending_teacher_id
        $this->forge->addKey('pending_teacher_id');
    }

    public function down()
    {
        $this->forge->dropColumn('courses', ['pending_teacher_id', 'teacher_assignment_status', 'teacher_assignment_requested_at']);
    }
}

