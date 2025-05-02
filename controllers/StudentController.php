<?php
require_once 'models/Student.class.php';
require_once 'models/Template.class.php';
require_once 'views/Student.view.php';

class StudentController {
    private $model;
    private $view;
    
    // Constructor
    public function __construct() {
        $this->model = new Student();
        $this->view = new StudentView();
    }
    
    // List all students
    public function index() {
        $students = $this->model->getAll();
        $this->view->displayList($students);
    }
    
    // Display create form
    public function create() {
        $this->view->displayCreateForm();
    }
    
    // Save new student
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model->create($_POST);
            
            if ($result) {
                header('Location: student.php?action=index&success=created');
                exit;
            } else {
                $this->view->displayCreateForm($_POST, 'Failed to create student.');
            }
        } else {
            header('Location: student.php?action=create');
            exit;
        }
    }
    
    // Display edit form
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: student.php?action=index');
            exit;
        }
        
        $id = $_GET['id'];
        $student = $this->model->getById($id);
        
        if (!$student) {
            header('Location: student.php?action=index&error=not_found');
            exit;
        }
        
        $this->view->displayEditForm($student);
    }
    
    // Update student
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $result = $this->model->update($id, $_POST);
            
            if ($result) {
                header('Location: student.php?action=index&success=updated');
                exit;
            } else {
                $student = $this->model->getById($id);
                $this->view->displayEditForm($student, 'Failed to update student.');
            }
        } else {
            header('Location: student.php?action=index');
            exit;
        }
    }
    
    // Delete student
    public function delete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->model->delete($id);
        }
        
        header('Location: student.php?action=index&success=deleted');
        exit;
    }
    
    // Show student details
    public function show() {
        if (!isset($_GET['id'])) {
            header('Location: student.php?action=index');
            exit;
        }
        
        $id = $_GET['id'];
        $student = $this->model->getById($id);
        
        if (!$student) {
            header('Location: student.php?action=index&error=not_found');
            exit;
        }
        
        // Get student's courses
        $courses = $this->model->getCourses($id);
        
        $this->view->displayDetails($student, $courses);
    }
    
    // Search students
    public function search() {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        
        if (!empty($keyword)) {
            $students = $this->model->search($keyword);
            $this->view->displayList($students, "Search results for: $keyword");
        } else {
            header('Location: student.php?action=index');
            exit;
        }
    }
}