<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAcademicYearsTable extends Migration
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
            'year_start' => [
                'type'       => 'INT',
                'constraint' => 4,
                'null'       => false,
                'comment'    => 'Starting year (e.g., 2024)'
            ],
            'year_end' => [
                'type'       => 'INT',
                'constraint' => 4,
                'null'       => false,
                'comment'    => 'Ending year (e.g., 2025)'
            ],
            'display_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'e.g., 2024-2025'
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
        $this->forge->addKey('is_active');
        $this->forge->addUniqueKey(['year_start', 'year_end']);
        $this->forge->createTable('academic_years', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('academic_years', true);
    }
}

