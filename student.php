<?php
require_once 'config/config.php';
require_once 'controllers/StudentController.php';

$controller = new StudentController();

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Route
switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'show':
        $controller->show();
        break;
    case 'search':
        $controller->search();
        break;
    case 'index':
    default:
        $controller->index();
        break;
}