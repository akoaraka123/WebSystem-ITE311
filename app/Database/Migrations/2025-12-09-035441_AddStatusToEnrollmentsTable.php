<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToEnrollmentsTable extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'accepted', 'rejected'],
                'default' => 'pending',
                'after' => 'enrollment_date'
            ]
        ];
        $this->forge->addColumn('enrollments', $fields);
        
        // Update existing enrollments to 'accepted' status (they were enrolled before this feature)
        $this->db->table('enrollments')
                 ->where('status', 'pending')
                 ->orWhere('status IS NULL')
                 ->update(['status' => 'accepted']);
    }

    public function down()
    {
        $this->forge->dropColumn('enrollments', 'status');
    }
}
