<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubmissionsTable extends Migration
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
            'quiz_id' => [
                'type' => 'INT', 'unsigned' => true,
            ],
            'answer_given' => [
                'type' => 'VARCHAR', 'constraint' => 255,
            ],
            'is_correct' => [
                'type' => 'BOOLEAN', 'default' => false,
            ],
            'submitted_at' => [
                'type' => 'DATETIME', 'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('submissions');
    }

    public function down()
    {
        $this->forge->dropTable('submissions');
    }
}
