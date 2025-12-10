<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses'; // ✅ dapat plural
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'teacher_id', 'program_id', 'acad_year_id', 'semester_id', 'term_id', 'course_number', 'schedule_time', 'schedule_time_start', 'schedule_time_end', 'duration', 'schedule_date', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get courses with academic information
    public function getCoursesWithAcademicInfo($courseIds = null)
    {
        $query = $this->select('courses.*, 
                            academic_years.display_name as acad_year_name,
                            semesters.name as semester_name,
                            terms.term_name,
                            courses.schedule_time,
                            courses.schedule_time_start,
                            courses.schedule_time_end,
                            courses.schedule_date,
                            courses.course_number,
                            courses.duration,
                            programs.code as program_code,
                            programs.name as program_name,
                            programs.id as program_id')
                    ->join('academic_years', 'academic_years.id = courses.acad_year_id', 'left')
                    ->join('semesters', 'semesters.id = courses.semester_id', 'left')
                    ->join('terms', 'terms.id = courses.term_id', 'left')
                    ->join('programs', 'programs.id = courses.program_id', 'left');
        
        // Filter by course IDs if provided (for students - only enrolled courses)
        if (!empty($courseIds) && is_array($courseIds)) {
            $query->whereIn('courses.id', $courseIds);
        }
        
        return $query->orderBy('programs.code', 'ASC')
                    ->orderBy('courses.title', 'ASC')
                    ->findAll();
    }

    // Get courses taught by a specific teacher
    public function getTeacherCourses($teacherId)
    {
        return $this->select('courses.*, 
                            academic_years.display_name as acad_year_name,
                            semesters.name as semester_name,
                            terms.term_name,
                            courses.schedule_time,
                            courses.schedule_time_start,
                            courses.schedule_time_end,
                            courses.schedule_date,
                            courses.course_number,
                            courses.duration,
                            programs.code as program_code,
                            programs.name as program_name,
                            programs.id as program_id')
                    ->join('academic_years', 'academic_years.id = courses.acad_year_id', 'left')
                    ->join('semesters', 'semesters.id = courses.semester_id', 'left')
                    ->join('terms', 'terms.id = courses.term_id', 'left')
                    ->join('programs', 'programs.id = courses.program_id', 'left')
                    ->where('courses.teacher_id', $teacherId)
                    ->orderBy('programs.code', 'ASC')
                    ->orderBy('courses.title', 'ASC')
                    ->findAll();
    }

    // Get available courses for students (courses they're not enrolled in)
    public function getAvailableCourses($enrolledIds = [])
    {
        $query = $this->select('courses.*, 
                            academic_years.display_name as acad_year_name,
                            semesters.name as semester_name,
                            terms.term_name,
                            courses.schedule_time,
                            courses.schedule_time_start,
                            courses.schedule_time_end,
                            courses.schedule_date,
                            courses.course_number,
                            courses.duration,
                            programs.code as program_code,
                            programs.name as program_name,
                            programs.id as program_id')
                    ->join('academic_years', 'academic_years.id = courses.acad_year_id', 'left')
                    ->join('semesters', 'semesters.id = courses.semester_id', 'left')
                    ->join('terms', 'terms.id = courses.term_id', 'left')
                    ->join('programs', 'programs.id = courses.program_id', 'left');
        
        if (!empty($enrolledIds)) {
            $query->whereNotIn('courses.id', $enrolledIds);
        }
        
        return $query->orderBy('programs.code', 'ASC')
                    ->orderBy('courses.title', 'ASC')
                    ->findAll();
    }

    // Count all courses
    public function countAll()
    {
        return $this->countAllResults();
    }

    /**
     * Check if teacher has time conflict with existing courses
     * @param int $teacherId Teacher ID
     * @param string $startTime Start time (HH:MM:SS format)
     * @param string $endTime End time (HH:MM:SS format)
     * @param string $scheduleDate Schedule date (YYYY-MM-DD format)
     * @param int|null $excludeCourseId Course ID to exclude from check (for updates)
     * @return array|false Returns conflicting course if found, false otherwise
     */
    public function checkTeacherTimeConflict($teacherId, $startTime, $endTime, $scheduleDate, $excludeCourseId = null)
    {
        // Get all courses for this teacher on the same date
        $query = $this->where('teacher_id', $teacherId)
                     ->where('schedule_date', $scheduleDate);
        
        // Exclude current course if updating
        if ($excludeCourseId) {
            $query->where('id !=', $excludeCourseId);
        }
        
        $existingCourses = $query->findAll();
        
        if (empty($existingCourses)) {
            return false;
        }
        
        // Convert times to minutes for easier comparison
        $newStartMinutes = $this->timeToMinutes($startTime);
        $newEndMinutes = $this->timeToMinutes($endTime);
        
        // Check each existing course for overlap
        foreach ($existingCourses as $course) {
            $existingStart = $course['schedule_time_start'] ?? $course['schedule_time'] ?? null;
            $existingEnd = $course['schedule_time_end'] ?? null;
            
            if (!$existingStart) {
                continue; // Skip if no start time
            }
            
            $existingStartMinutes = $this->timeToMinutes($existingStart);
            $existingEndMinutes = $existingEnd ? $this->timeToMinutes($existingEnd) : null;
            
            // If no end time, assume 1 hour duration
            if (!$existingEndMinutes) {
                $existingEndMinutes = $existingStartMinutes + 60;
            }
            
            // Check for overlap
            // Overlap occurs if:
            // 1. New start is between existing start and end
            // 2. New end is between existing start and end
            // 3. New range completely contains existing range
            // 4. Existing range completely contains new range
            if (($newStartMinutes >= $existingStartMinutes && $newStartMinutes < $existingEndMinutes) ||
                ($newEndMinutes > $existingStartMinutes && $newEndMinutes <= $existingEndMinutes) ||
                ($newStartMinutes <= $existingStartMinutes && $newEndMinutes >= $existingEndMinutes) ||
                ($newStartMinutes >= $existingStartMinutes && $newEndMinutes <= $existingEndMinutes)) {
                return $course;
            }
        }
        
        return false;
    }
    
    /**
     * Convert time string (HH:MM:SS or HH:MM) to minutes
     * @param string $time Time string
     * @return int Minutes since midnight
     */
    private function timeToMinutes($time)
    {
        if (empty($time)) {
            return 0;
        }
        
        $parts = explode(':', $time);
        $hours = (int) ($parts[0] ?? 0);
        $minutes = (int) ($parts[1] ?? 0);
        
        return ($hours * 60) + $minutes;
    }
}
