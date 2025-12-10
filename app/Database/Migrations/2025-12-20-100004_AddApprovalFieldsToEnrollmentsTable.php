<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApprovalFieldsToEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('enrollments', [
            'teacher_approved' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'status',
                'comment'    => '1 = approved by teacher, 0 = not approved'
            ],
            'admin_approved' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'teacher_approved',
                'comment'    => '1 = approved by admin, 0 = not approved'
            ],
            'teacher_approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'teacher_approved',
            ],
            'admin_approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'admin_approved',
            ],
        ]);

        // Add indexes for faster queries
        $this->forge->addKey('teacher_approved');
        $this->forge->addKey('admin_approved');
    }

    public function down()
    {
        $this->forge->dropColumn('enrollments', ['teacher_approved', 'admin_approved', 'teacher_approved_at', 'admin_approved_at']);
    }
}

