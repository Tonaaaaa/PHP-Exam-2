<?php
// Start session
session_start();

// Include database and models
require_once __DIR__ . '/../src/config/database.php';

// Load controllers
require_once __DIR__ . '/../src/controllers/StudentController.php';
require_once __DIR__ . '/../src/controllers/CourseController.php';
require_once __DIR__ . '/../src/controllers/RegistrationController.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/MajorController.php';

// Get controller and action from URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'student';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Load appropriate controller
switch ($controller) {
    case 'student':
        $studentController = new StudentController();
        break;
    case 'course':
        $courseController = new CourseController();
        break;
    case 'registration':
        $registrationController = new RegistrationController();
        break;
    case 'auth':
        $authController = new AuthController();
        break;
    case 'major':
        $majorController = new MajorController();
        break;
    default:
        // Default to student controller
        $studentController = new StudentController();
        $action = 'index';
}

// Call appropriate action
switch ($controller) {
    case 'student':
        if (empty($action) || $action === 'index') {
            $studentController->index();
        } elseif ($action === 'create') {
            $studentController->create();
        } elseif ($action === 'store') {
            $studentController->store();
        } elseif ($action === 'edit') {
            $studentController->edit();
        } elseif ($action === 'update') {
            $studentController->update();
        } elseif ($action === 'delete') {
            $studentController->delete();
        } elseif ($action === 'show') {
            $studentController->show();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
        break;

    case 'course':
        if (empty($action) || $action === 'index') {
            $courseController->index();
        } elseif ($action === 'register') {
            $courseController->register();
        } elseif ($action === 'manage') {
            $courseController->manage();
        } elseif ($action === 'create') {
            $courseController->create();
        } elseif ($action === 'store') {
            $courseController->store();
        } elseif ($action === 'edit') {
            $courseController->edit();
        } elseif ($action === 'update') {
            $courseController->update();
        } elseif ($action === 'delete') {
            $courseController->delete();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
        break;

    case 'registration':
        if (empty($action) || $action === 'index') {
            $registrationController->index();
        } elseif ($action === 'store') {
            $registrationController->store();
        } elseif ($action === 'success') {
            $registrationController->success();
        } elseif ($action === 'cancelCourse') {
            $registrationController->cancelCourse();
        } elseif ($action === 'cancelAll') {
            $registrationController->cancelAll();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
        break;

    case 'auth':
        if ($action === 'login') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            } else {
                $authController->showLoginForm();
            }
        } elseif ($action === 'logout') {
            $authController->logout();
        } else {
            $authController->showLoginForm();
        }
        break;

    case 'major':
        if (empty($action) || $action === 'index') {
            $majorController->index();
        } elseif ($action === 'create') {
            $majorController->create();
        } elseif ($action === 'store') {
            $majorController->store();
        } elseif ($action === 'edit') {
            $majorController->edit();
        } elseif ($action === 'update') {
            $majorController->update();
        } elseif ($action === 'delete') {
            $majorController->delete();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
