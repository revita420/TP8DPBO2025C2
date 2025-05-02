<?php
require_once 'DB.class.php';

class Enrollment {
    private $db;
    
    // Constructor
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    // Get all enrollments with student and course information
    public function getAll() {
        $sql = "SELECT e.*, s.name as student_name, s.nim, c.course_code, c.course_name 
                FROM enrollments e
                JOIN students s ON e.student_id = s.id
                JOIN courses c ON e.course_id = c.id
                ORDER BY s.name, c.course_name";
                
        $result = $this->db->query($sql);
        
        $enrollments = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $enrollments[] = $row;
            }
        }
        
        return $enrollments;
    }
    
    // Get enrollment by ID
    public function getById($id) {
        $id = $this->db->escapeString($id);
        $sql = "SELECT e.*, s.name as student_name, s.nim, c.course_code, c.course_name 
                FROM enrollments e
                JOIN students s ON e.student_id = s.id
                JOIN courses c ON e.course_id = c.id
                WHERE e.id = $id";
                
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Create new enrollment
    public function create($data) {
        $student_id = (int)$data['student_id'];
        $course_id = (int)$data['course_id'];
        $enrollment_date = $this->db->escapeString($data['enrollment_date']);
        $grade = isset($data['grade']) ? "'" . $this->db->escapeString($data['grade']) . "'" : "NULL";
        
        // Check if enrollment already exists
        $check_sql = "SELECT id FROM enrollments 
                      WHERE student_id = $student_id AND course_id = $course_id";
        $check_result = $this->db->query($check_sql);
        
        if ($check_result && $check_result->num_rows > 0) {
            return false; // Enrollment already exists
        }
        
        $sql = "INSERT INTO enrollments (student_id, course_id, enrollment_date, grade) 
                VALUES ($student_id, $course_id, '$enrollment_date', $grade)";
                
        if ($this->db->query($sql)) {
            return $this->db->getLastId();
        }
        
        return false;
    }
    
    // Update enrollment
    public function update($id, $data) {
        $id = $this->db->escapeString($id);
        $student_id = (int)$data['student_id'];
        $course_id = (int)$data['course_id'];
        $enrollment_date = $this->db->escapeString($data['enrollment_date']);
        $grade = isset($data['grade']) ? "'" . $this->db->escapeString($data['grade']) . "'" : "NULL";
        
        // Check if new student_id and course_id combination already exists in another enrollment
        $check_sql = "SELECT id FROM enrollments 
                      WHERE student_id = $student_id AND course_id = $course_id AND id != $id";
        $check_result = $this->db->query($check_sql);
        
        if ($check_result && $check_result->num_rows > 0) {
            return false; // Another enrollment with the same student and course exists
        }
        
        $sql = "UPDATE enrollments 
                SET student_id = $student_id, 
                    course_id = $course_id, 
                    enrollment_date = '$enrollment_date', 
                    grade = $grade 
                WHERE id = $id";
                
        return $this->db->query($sql);
    }
    
    // Delete enrollment
    public function delete($id) {
        $id = $this->db->escapeString($id);
        $sql = "DELETE FROM enrollments WHERE id = $id";
        
        return $this->db->query($sql);
    }
    
    // Get available courses for a student (not yet enrolled)
    public function getAvailableCoursesForStudent($studentId) {
        $studentId = $this->db->escapeString($studentId);
        
        $sql = "SELECT * FROM courses 
                WHERE id NOT IN (
                    SELECT course_id FROM enrollments WHERE student_id = $studentId
                )
                ORDER BY course_name";
                
        $result = $this->db->query($sql);
        
        $courses = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
        }
        
        return $courses;
    }
    
}