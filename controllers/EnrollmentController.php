<?php
require_once 'models/Enrollment.class.php';
require_once 'models/Student.class.php';
require_once 'models/Course.class.php';
require_once 'models/Template.class.php';
require_once 'views/Enrollment.view.php';

class EnrollmentController {
    private $model;
    private $studentModel;
    private $courseModel;
    private $view;
    
    // Constructor
    public function __construct() {
        $this->model = new Enrollment();
        $this->studentModel = new Student();
        $this->courseModel = new Course();
        $this->view = new EnrollmentView();
    }
    
    // List all enrollments
    public function index() {
        $enrollments = $this->model->getAll();
        $this->view->displayList($enrollments);
    }
    
    // Display create form
    public function create() {
        $students = $this->studentModel->getAll();
        $courses = $this->courseModel->getAll();
        $this->view->displayCreateForm($students, $courses);
    }
    
    // Save new enrollment
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model->create($_POST);
            
            if ($result) {
                // Redirect based on context
                if (isset($_POST['from_student']) && $_POST['from_student'] == '1') {
                    header('Location: student.php?action=show&id=' . $_POST['student_id'] . '&success=enrolled');
                } else {
                    header('Location: enrollment.php?action=index&success=created');
                }
                exit;
            } else {
                $students = $this->studentModel->getAll();
                $courses = $this->courseModel->getAll();
                $this->view->displayCreateForm($students, $courses, 'Failed to create enrollment. Student may already be enrolled in this course.');
            }
        } else {
            header('Location: enrollment.php?action=create');
            exit;
        }
    }
    
    // Display edit form
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: enrollment.php?action=index');
            exit;
        }
        
        $id = $_GET['id'];
        $enrollment = $this->model->getById($id);
        
        if (!$enrollment) {
            header('Location: enrollment.php?action=index&error=not_found');
            exit;
        }
        
        $students = $this->studentModel->getAll();
        $courses = $this->courseModel->getAll();
        
        $this->view->displayEditForm($enrollment, $students, $courses);
    }
    
    // Update enrollment
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $result = $this->model->update($id, $_POST);
            
            if ($result) {
                header('Location: enrollment.php?action=index&success=updated');
                exit;
            } else {
                $enrollment = $this->model->getById($id);
                $students = $this->studentModel->getAll();
                $courses = $this->courseModel->getAll();
                $this->view->displayEditForm($enrollment, $students, $courses, 'Failed to update enrollment. Check if the student is already enrolled in this course.');
            }
        } else {
            header('Location: enrollment.php?action=index');
            exit;
        }
    }
    
    // Delete enrollment
    public function delete() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $enrollment = $this->model->getById($id);
            $this->model->delete($id);
            
            // Redirect based on context
            if (isset($_GET['from_student']) && $_GET['from_student'] == '1') {
                header('Location: student.php?action=show&id=' . $enrollment['student_id'] . '&success=unenrolled');
            } else {
                header('Location: enrollment.php?action=index&success=deleted');
            }
            exit;
        }
        
        header('Location: enrollment.php?action=index');
        exit;
    }
}