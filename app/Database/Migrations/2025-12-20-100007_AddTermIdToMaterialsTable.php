<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTermIdToMaterialsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('materials', [
            'term_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'course_id',
                'comment'    => 'Term ID (PRELIM, MIDTERM, FINAL)'
            ],
        ]);

        // Add foreign key constraint (optional)
        // $this->forge->addForeignKey('term_id', 'terms', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropColumn('materials', 'term_id');
    }
}

