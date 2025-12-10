<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTermsTable extends Migration
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
            'semester_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'term_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'e.g., Prelim, Midterm, Finals'
            ],
            'term_order' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
                'null'       => false,
                'comment'    => 'Order within semester (1, 2, 3, etc.)'
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1 = active, 0 = inactive'
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
        $this->forge->addKey('semester_id');
        $this->forge->addKey('is_active');
        $this->forge->addForeignKey('semester_id', 'semesters', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['semester_id', 'term_order']);
        $this->forge->createTable('terms', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('terms', true);
    }
}

