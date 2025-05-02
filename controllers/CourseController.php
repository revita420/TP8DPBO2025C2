<?php
require_once 'models/Course.class.php';
require_once 'models/Template.class.php';
require_once 'views/Course.view.php';

class CourseController {
    private $model;
    private $view;
    
    // Constructor
    public function __construct() {
        $this->model = new Course();
        $this->view = new CourseView();
    }
    
    // List all courses
    public function index() {
        $courses = $this->model->getAll();
        $this->view->displayList($courses);
    }
    
    // Display create form
    public function create() {
        $this->view->displayCreateForm();
    }
    
    // Save new course
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model->create($_POST);
            
            if ($result) {
                header('Location: course.php?action=index&success=created');
                exit;
            } else {
                $this->view->displayCreateForm($_POST, 'Failed to create course.');
            }
        } else {
            header('Location: course.php?action=create');
            exit;
        }
    }
    
    // Display edit form
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: course.php?action=index');
            exit;
        }
        
        $id = $_GET['id'];
        $course = $this->model->getById($id);
        
        if (!$course) {
            header('Location: course.php?action=index&error=not_found');
            exit;
        }
        
        $this->view->displayEditForm($course);
    }
    
    // Update course
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $result = $this->model->update($id, $_POST);
            
            if ($result) {
                header('Location: course.php?action=index&success=updated');
                exit;
            } else {
                $course = $this->model->getById($id);
                $this->view->displayEditForm($course, 'Failed to update course.');
            }
        } else {
            header('Location: course.php?action=index');
            exit;
        }
    }
    
    // Delete course
    public function delete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->model->delete($id);
        }
        
        header('Location: course.php?action=index&success=deleted');
        exit;
    }
    
    // Show course details
    public function show() {
        if (!isset($_GET['id'])) {
            header('Location: course.php?action=index');
            exit;
        }
        
        $id = $_GET['id'];
        $course = $this->model->getById($id);
        
        if (!$course) {
            header('Location: course.php?action=index&error=not_found');
            exit;
        }
        
        // Get students enrolled in this course
        $students = $this->model->getStudents($id);
        
        $this->view->displayDetails($course, $students);
    }
    
    // Search courses
    public function search() {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        
        if (!empty($keyword)) {
            $courses = $this->model->search($keyword);
            $this->view->displayList($courses, "Search results for: $keyword");
        } else {
            header('Location: course.php?action=index');
            exit;
        }
    }
}