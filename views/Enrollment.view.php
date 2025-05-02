<?php
require_once 'models/Template.class.php';

class EnrollmentView {
    
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
    
    // Display list of enrollments
    public function displayList($enrollments, $title = 'Enrollment List') {
        $data = [
            'title' => $title,
            'enrollments' => $enrollments,
            'message' => '',
            'messageType' => ''
        ];
        
        // Handle success/error messages
        if (isset($_GET['success'])) {
            $data['messageType'] = 'success';
            switch ($_GET['success']) {
                case 'created':
                    $data['message'] = 'Enrollment created successfully.';
                    break;
                case 'updated':
                    $data['message'] = 'Enrollment updated successfully.';
                    break;
                case 'deleted':
                    $data['message'] = 'Enrollment deleted successfully.';
                    break;
                case 'grade_updated':
                    $data['message'] = 'Grade updated successfully.';
                    break;
                default:
                    $data['message'] = 'Operation successful.';
            }
        } elseif (isset($_GET['error'])) {
            $data['messageType'] = 'danger';
            switch ($_GET['error']) {
                case 'not_found':
                    $data['message'] = 'Enrollment not found.';
                    break;
                case 'grade_update_failed':
                    $data['message'] = 'Failed to update grade.';
                    break;
                default:
                    $data['message'] = 'An error occurred.';
            }
        }
        
        echo $this->renderWithBase('enrollment/list.html', $data);
    }
    
    // Display create enrollment form
    public function displayCreateForm($students, $courses, $error = '') {
        $templateData = [
            'title' => 'Create Enrollment',
            'students' => $students,
            'courses' => $courses,
            'error' => $error,
            'from_student' => false,
            'message' => $error,
            'messageType' => !empty($error) ? 'danger' : ''
        ];
        
        echo $this->renderWithBase('enrollment/create.html', $templateData);
    }
    
    // Display edit enrollment form
    public function displayEditForm($enrollment, $students, $courses, $error = '') {
        $templateData = [
            'title' => 'Edit Enrollment',
            'enrollment' => $enrollment,
            'students' => $students,
            'courses' => $courses,
            'error' => $error,
            'message' => $error,
            'messageType' => !empty($error) ? 'danger' : ''
        ];
        
        echo $this->renderWithBase('enrollment/edit.html', $templateData);
    }
}