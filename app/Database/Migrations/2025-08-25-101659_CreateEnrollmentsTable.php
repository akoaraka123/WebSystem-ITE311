<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'student_id' => ['type' => 'INT', 'null' => false], // renamed from user_id
            'course_id'  => ['type' => 'INT', 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('enrollments');
    }

    public function down()
    {
        $this->forge->dropTable('enrollments');
    }
}
