<?php
require_once 'config/config.php';
require_once 'models/Template.class.php';
require_once 'models/Student.class.php';
require_once 'models/Course.class.php';

// Get data for dashboard
$studentModel = new Student();
$courseModel = new Course();

$students = $studentModel->getAll();
$courses = $courseModel->getAll();

// Render home page template
$template = new Template('home.html');
$template->set('title', 'Dashboard');
$template->set('studentCount', count($students));
$template->set('courseCount', count($courses));
$template->set('recentStudents', array_slice($students, 0, 5)); // Get 5 most recent students

// Get content from template
$content = $template->render();

// Render base template with content
$baseTemplate = new Template('base.html');
$baseTemplate->set('title', 'Dashboard');
$baseTemplate->set('content', $content);
echo $baseTemplate->render();