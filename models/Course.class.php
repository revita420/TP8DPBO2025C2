<?php
require_once 'DB.class.php';

class Course {
    private $db;
    
    // Constructor
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    // Get all courses
    public function getAll() {
        $sql = "SELECT * FROM courses ORDER BY course_name";
        $result = $this->db->query($sql);
        
        $courses = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
        }
        
        return $courses;
    }
    
    // Get course by ID
    public function getById($id) {
        $id = $this->db->escapeString($id);
        $sql = "SELECT * FROM courses WHERE id = $id";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Create new course
    public function create($data) {
        $course_code = $this->db->escapeString($data['course_code']);
        $course_name = $this->db->escapeString($data['course_name']);
        $credits = (int)$data['credits'];
        $description = $this->db->escapeString($data['description']);
        
        $sql = "INSERT INTO courses (course_code, course_name, credits, description) 
                VALUES ('$course_code', '$course_name', $credits, '$description')";
                
        if ($this->db->query($sql)) {
            return $this->db->getLastId();
        }
        
        return false;
    }
    
    // Update course
    public function update($id, $data) {
        $id = $this->db->escapeString($id);
        $course_code = $this->db->escapeString($data['course_code']);
        $course_name = $this->db->escapeString($data['course_name']);
        $credits = (int)$data['credits'];
        $description = $this->db->escapeString($data['description']);
        
        $sql = "UPDATE courses 
                SET course_code = '$course_code', 
                    course_name = '$course_name', 
                    credits = $credits, 
                    description = '$description' 
                WHERE id = $id";
                
        return $this->db->query($sql);
    }
    
    // Delete course
    public function delete($id) {
        $id = $this->db->escapeString($id);
        $sql = "DELETE FROM courses WHERE id = $id";
        
        return $this->db->query($sql);
    }
    
    // Search courses
    public function search($keyword) {
        $keyword = $this->db->escapeString($keyword);
        $sql = "SELECT * FROM courses 
                WHERE course_code LIKE '%$keyword%' 
                OR course_name LIKE '%$keyword%' 
                OR description LIKE '%$keyword%'
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
    
    // Get students enrolled in a course
    public function getStudents($courseId) {
        $courseId = $this->db->escapeString($courseId);
        
        $sql = "SELECT s.*, e.enrollment_date, e.grade
                FROM students s
                JOIN enrollments e ON s.id = e.student_id
                WHERE e.course_id = $courseId
                ORDER BY s.name";
                
        $result = $this->db->query($sql);
        
        $students = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
        
        return $students;
    }
}