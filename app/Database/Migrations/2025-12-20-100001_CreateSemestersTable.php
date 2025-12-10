<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSemestersTable extends Migration
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
            'acad_year_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'semester_number' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'comment'    => '1 = 1st Sem, 2 = 2nd Sem'
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'e.g., 1st Semester, 2nd Semester'
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
        $this->forge->addKey('acad_year_id');
        $this->forge->addKey('is_active');
        $this->forge->addForeignKey('acad_year_id', 'academic_years', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['acad_year_id', 'semester_number']);
        $this->forge->createTable('semesters', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('semesters', true);
    }
}

