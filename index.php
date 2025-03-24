<?php
// Hiển thị tất cả lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Include configuration
require_once 'config/database.php';

// Include models
require_once 'models/Database.php';
require_once 'models/Student.php';
require_once 'models/Course.php';
require_once 'models/Registration.php';

// Include controllers
require_once 'controllers/StudentController.php';
require_once 'controllers/CourseController.php';
require_once 'controllers/RegistrationController.php';
require_once 'controllers/AuthController.php';

// Routing
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'student';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Initialize controller
switch($controller) {
    case 'student':
        $controller = new StudentController();
        break;
    case 'course':
        $controller = new CourseController();
        break;
    case 'registration':
        $controller = new RegistrationController();
        break;
    case 'auth':
        $controller = new AuthController();
        break;
    default:
        $controller = new StudentController();
}

// Call action
if($id) {
    $controller->$action($id);
} else {
    $controller->$action();
}
?>
