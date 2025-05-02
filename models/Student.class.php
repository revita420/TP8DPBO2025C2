<?php
require_once 'DB.class.php';

class Student {
    private $db;
    
    // Constructor
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    // Get all students
    public function getAll() {
        $sql = "SELECT * FROM students ORDER BY name";
        $result = $this->db->query($sql);
        
        $students = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
        
        return $students;
    }
    
    // Get student by ID
    public function getById($id) {
        $id = $this->db->escapeString($id);
        $sql = "SELECT * FROM students WHERE id = $id";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Create new student
    public function create($data) {
        $name = $this->db->escapeString($data['name']);
        $nim = $this->db->escapeString($data['nim']);
        $phone = $this->db->escapeString($data['phone']);
        $join_date = $this->db->escapeString($data['join_date']);
        $semester = isset($data['semester']) ? (int)$data['semester'] : 1;
        $email = $this->db->escapeString($data['email']);
        
        $sql = "INSERT INTO students (name, nim, phone, join_date, semester, email) 
                VALUES ('$name', '$nim', '$phone', '$join_date', $semester, '$email')";
                
        if ($this->db->query($sql)) {
            return $this->db->getLastId();
        }
        
        return false;
    }
    
    // Update student
    public function update($id, $data) {
        $id = $this->db->escapeString($id);
        $name = $this->db->escapeString($data['name']);
        $nim = $this->db->escapeString($data['nim']);
        $phone = $this->db->escapeString($data['phone']);
        $join_date = $this->db->escapeString($data['join_date']);
        $semester = isset($data['semester']) ? (int)$data['semester'] : 1;
        $email = $this->db->escapeString($data['email']);
        
        $sql = "UPDATE students 
                SET name = '$name', nim = '$nim', phone = '$phone', 
                    join_date = '$join_date', semester = $semester, email = '$email' 
                WHERE id = $id";
                
        return $this->db->query($sql);
    }
    
    // Delete student
    public function delete($id) {
        $id = $this->db->escapeString($id);
        $sql = "DELETE FROM students WHERE id = $id";
        
        return $this->db->query($sql);
    }
    
    // Search students
    public function search($keyword) {
        $keyword = $this->db->escapeString($keyword);
        $sql = "SELECT * FROM students 
                WHERE name LIKE '%$keyword%' 
                OR nim LIKE '%$keyword%' 
                OR email LIKE '%$keyword%'
                ORDER BY name";
                
        $result = $this->db->query($sql);
        
        $students = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
        
        return $students;
    }
    
    // Get student courses
    public function getCourses($studentId) {
        $studentId = $this->db->escapeString($studentId);
        
        $sql = "SELECT c.*, e.enrollment_date, e.grade
                FROM courses c
                JOIN enrollments e ON c.id = e.course_id
                WHERE e.student_id = $studentId
                ORDER BY c.course_name";
                
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