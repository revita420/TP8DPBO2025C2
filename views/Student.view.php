<?php
require_once 'models/Template.class.php';

class StudentView {
    
    // base template
    private function renderWithBase($contentTemplate, $data = []) {
        $template = new Template('base.html');
        
        if (isset($data['title'])) {
            $template->set('title', $data['title']);
        }
        
        // Set message
        if (isset($data['message']) && isset($data['messageType'])) {
            $template->set('message', $data['message']);
            $template->set('messageType', $data['messageType']);
        }

        $content = $template->renderPartial($contentTemplate, $data);
        $template->set('content', $content);

        return $template->render();
    }
    
    // Display list of students
    public function displayList($students, $title = 'Student List') {
        $data = [
            'title' => $title,
            'students' => $students,
            'message' => '',
            'messageType' => ''
        ];
        
        // Handle success/error messages
        if (isset($_GET['success'])) {
            $data['messageType'] = 'success';
            switch ($_GET['success']) {
                case 'created':
                    $data['message'] = 'Student created successfully.';
                    break;
                case 'updated':
                    $data['message'] = 'Student updated successfully.';
                    break;
                case 'deleted':
                    $data['message'] = 'Student deleted successfully.';
                    break;
                default:
                    $data['message'] = 'Operation successful.';
            }
        } elseif (isset($_GET['error'])) {
            $data['messageType'] = 'danger';
            switch ($_GET['error']) {
                case 'not_found':
                    $data['message'] = 'Student not found.';
                    break;
                default:
                    $data['message'] = 'An error occurred.';
            }
        }
        
        echo $this->renderWithBase('student/list.html', $data);
    }
    
    // Display create form
    public function displayCreateForm($data = null, $error = '') {
        $templateData = [
            'title' => 'Create Student',
            'data' => $data,
            'error' => $error,
            'message' => $error,
            'messageType' => !empty($error) ? 'danger' : ''
        ];
        
        echo $this->renderWithBase('student/create.html', $templateData);
    }
    
    // Display edit form
    public function displayEditForm($student, $error = '') {
        $templateData = [
            'title' => 'Edit Student',
            'student' => $student,
            'error' => $error,
            'message' => $error,
            'messageType' => !empty($error) ? 'danger' : ''
        ];
        
        echo $this->renderWithBase('student/edit.html', $templateData);
    }
    
    // Display student details with enrolled courses
    public function displayDetails($student, $courses) {
        $templateData = [
            'title' => 'Student Details',
            'student' => $student,
            'courses' => $courses,
            'message' => '',
            'messageType' => ''
        ];
        
        // Handle success/error messages
        if (isset($_GET['success'])) {
            $templateData['messageType'] = 'success';
            switch ($_GET['success']) {
                case 'enrolled':
                    $templateData['message'] = 'Student enrolled in course successfully.';
                    break;
                case 'unenrolled':
                    $templateData['message'] = 'Student unenrolled from course successfully.';
                    break;
                default:
                    $templateData['message'] = 'Operation successful.';
            }
        }
        
        echo $this->renderWithBase('student/details.html', $templateData);
    }
}