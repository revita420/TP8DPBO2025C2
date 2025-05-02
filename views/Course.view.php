<?php
require_once 'models/Template.class.php';

class CourseView {
    
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

    // Display list of courses
    public function displayList($courses, $title = 'Course List') {
        $data = [
            'title' => $title,
            'courses' => $courses,
            'message' => '',
            'messageType' => ''
        ];
        
        // Handle success/error messages
        if (isset($_GET['success'])) {
            $data['messageType'] = 'success';
            switch ($_GET['success']) {
                case 'created':
                    $data['message'] = 'Course created successfully.';
                    break;
                case 'updated':
                    $data['message'] = 'Course updated successfully.';
                    break;
                case 'deleted':
                    $data['message'] = 'Course deleted successfully.';
                    break;
                default:
                    $data['message'] = 'Operation successful.';
            }
        } elseif (isset($_GET['error'])) {
            $data['messageType'] = 'danger';
            switch ($_GET['error']) {
                case 'not_found':
                    $data['message'] = 'Course not found.';
                    break;
                default:
                    $data['message'] = 'An error occurred.';
            }
        }
        
        echo $this->renderWithBase('course/list.html', $data);
    }
    
    // Display create form
    public function displayCreateForm($data = null, $error = '') {
        $templateData = [
            'title' => 'Create Course',
            'data' => $data,
            'error' => $error,
            'message' => $error,
            'messageType' => !empty($error) ? 'danger' : ''
        ];
        
        echo $this->renderWithBase('course/create.html', $templateData);
    }
    
    // Display edit form
    public function displayEditForm($course, $error = '') {
        $templateData = [
            'title' => 'Edit Course',
            'course' => $course,
            'error' => $error,
            'message' => $error,
            'messageType' => !empty($error) ? 'danger' : ''
        ];
        
        echo $this->renderWithBase('course/edit.html', $templateData);
    }
    
    // Display course details with enrolled students
    public function displayDetails($course, $students) {
        $templateData = [
            'title' => 'Course Details',
            'course' => $course,
            'students' => $students
        ];
        
        echo $this->renderWithBase('course/details.html', $templateData);
    }
}