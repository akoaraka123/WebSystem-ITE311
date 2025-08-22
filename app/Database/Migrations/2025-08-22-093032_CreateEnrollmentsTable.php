<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true,
            ],
            'student_id' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'course_id' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'enrolled_at' => [
                'type' => 'DATETIME', 'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('enrollments');
    }

    public function down()
    {
        $this->forge->dropTable('enrollments');
    }
}
