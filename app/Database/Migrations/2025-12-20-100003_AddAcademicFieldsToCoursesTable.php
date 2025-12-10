<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAcademicFieldsToCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'acad_year_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'program_id',
            ],
            'semester_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'acad_year_id',
            ],
            'term_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'semester_id',
            ],
            'course_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'term_id',
                'comment'    => 'Course Number / Section Code (e.g., IT101-A)'
            ],
            'schedule_time' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'course_number',
                'comment' => 'Class time (e.g., 08:00:00)'
            ],
            'schedule_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'schedule_time',
                'comment' => 'Class date or start date'
            ],
        ]);

        // Add foreign keys
        $this->forge->addForeignKey('acad_year_id', 'academic_years', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('semester_id', 'semesters', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('term_id', 'terms', 'id', 'SET NULL', 'CASCADE');
        
        // Add index for course_number for faster searches
        $this->forge->addKey('course_number');
    }

    public function down()
    {
        // Drop foreign keys first
        $this->forge->dropForeignKey('courses', 'courses_acad_year_id_foreign');
        $this->forge->dropForeignKey('courses', 'courses_semester_id_foreign');
        $this->forge->dropForeignKey('courses', 'courses_term_id_foreign');
        
        // Drop columns
        $this->forge->dropColumn('courses', ['acad_year_id', 'semester_id', 'term_id', 'course_number', 'schedule_time', 'schedule_date']);
    }
}

