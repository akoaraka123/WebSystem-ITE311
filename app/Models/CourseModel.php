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
                            programs.id as program_id,
                            users.name as teacher_name,
                            users.id as teacher_user_id')
                    ->join('academic_years', 'academic_years.id = courses.acad_year_id', 'left')
                    ->join('semesters', 'semesters.id = courses.semester_id', 'left')
                    ->join('terms', 'terms.id = courses.term_id', 'left')
                    ->join('programs', 'programs.id = courses.program_id', 'left')
                    ->join('users', 'users.id = courses.teacher_id', 'left');
        
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
                            programs.id as program_id,
                            users.name as teacher_name,
                            users.id as teacher_user_id')
                    ->join('academic_years', 'academic_years.id = courses.acad_year_id', 'left')
                    ->join('semesters', 'semesters.id = courses.semester_id', 'left')
                    ->join('terms', 'terms.id = courses.term_id', 'left')
                    ->join('programs', 'programs.id = courses.program_id', 'left')
                    ->join('users', 'users.id = courses.teacher_id', 'left')
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
     * @param int|null $acadYearId Academic Year ID - only check conflicts within the same academic year
     * @return array|false Returns conflicting course if found, false otherwise
     */
    public function checkTeacherTimeConflict($teacherId, $startTime, $endTime, $scheduleDate, $excludeCourseId = null, $acadYearId = null)
    {
        // Validate inputs
        if (empty($teacherId) || empty($startTime) || empty($scheduleDate)) {
            return false; // Cannot check without required fields
        }
        
        // Get all courses for this teacher on the same date and same academic year
        $query = $this->where('teacher_id', $teacherId)
                     ->where('schedule_date', $scheduleDate);
        
        // Only check conflicts within the same academic year
        // If acadYearId is provided, filter by it. If not provided, only check courses without academic year.
        if ($acadYearId !== null) {
            $query->where('acad_year_id', $acadYearId);
        } else {
            // If no academic year specified, only check courses that also have no academic year
            // Use raw SQL for NULL check
            $query->where('acad_year_id IS NULL', null, false);
        }
        
        // Exclude current course if updating
        if ($excludeCourseId) {
            $query->where('id !=', $excludeCourseId);
        }
        
        $existingCourses = $query->findAll();
        
        if (empty($existingCourses)) {
            return false; // No existing courses, no conflict
        }
        
        // Convert times to minutes for easier comparison
        $newStartMinutes = $this->timeToMinutes($startTime);
        $newEndMinutes = !empty($endTime) ? $this->timeToMinutes($endTime) : ($newStartMinutes + 60); // Default 1 hour if no end time
        
        // Validate time range
        if ($newEndMinutes <= $newStartMinutes) {
            return false; // Invalid time range
        }
        
        // Check each existing course for conflict
        foreach ($existingCourses as $course) {
            $existingStart = $course['schedule_time_start'] ?? $course['schedule_time'] ?? null;
            $existingEnd = $course['schedule_time_end'] ?? null;
            
            if (!$existingStart) {
                continue; // Skip if no start time
            }
            
            $existingStartMinutes = $this->timeToMinutes($existingStart);
            $existingEndMinutes = $existingEnd ? $this->timeToMinutes($existingEnd) : ($existingStartMinutes + 60);
            
            // Validate existing time range
            if ($existingEndMinutes <= $existingStartMinutes) {
                continue; // Skip invalid time ranges
            }
            
            // Check for overlap or conflict (any overlap or adjacent times are conflicts)
            // Two time ranges conflict if:
            // 1. They overlap (share any common time)
            // 2. They are adjacent (one ends exactly when another starts - teacher needs time between classes)
            // 
            // Examples of conflicts:
            // - Existing 4:00-6:00, New 5:00-7:00 -> Overlaps (5:00-6:00)
            // - Existing 4:14-4:15, New 4:15-5:16 -> Adjacent (4:15 is end of first and start of second)
            // - Existing 4:00-5:00, New 4:30-5:30 -> Overlaps (4:30-5:00)
            // - Existing 4:00-6:00, New 3:00-5:00 -> Overlaps (4:00-5:00)
            
            // Overlap check: ranges overlap if newStart < existingEnd AND newEnd > existingStart
            // Adjacent check: newStart == existingEnd OR newEnd == existingStart (teacher can't teach back-to-back)
            $hasOverlap = ($newStartMinutes < $existingEndMinutes && $newEndMinutes > $existingStartMinutes);
            $isAdjacent = ($newStartMinutes == $existingEndMinutes || $newEndMinutes == $existingStartMinutes);
            
            if ($hasOverlap || $isAdjacent) {
                return $course; // Time conflict detected (overlap or adjacent)
            }
        }
        
        return false; // No conflicts found
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
