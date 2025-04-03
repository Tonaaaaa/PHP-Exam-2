<?php

require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../config/database.php';

class AuthController
{
    private $db;
    private $student;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->student = new Student($this->db);
    }

    // Display login form
    public function showLoginForm()
    {
        // Include view
        include __DIR__ . '/../views/auth/login.php';
    }

    // Process login
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $maSV = $_POST['maSV'];
            $matKhau = $_POST['matKhau'];

            // Validate login
            $student = $this->student->validateLogin($maSV, $matKhau);

            if ($student) {
                // Set session variables
                $_SESSION['student_id'] = $student['MaSV'];
                $_SESSION['student_name'] = $student['HoTen'];

                // Redirect to course list
                header('Location: index.php?controller=course&action=index');
                exit;
            } else {
                // Error message
                $error = "Mã sinh viên hoặc mật khẩu không đúng.";

                // Include view
                include __DIR__ . '/../views/auth/login.php';
            }
        } else {
            $this->showLoginForm();
        }
    }

    // Logout
    public function logout()
    {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}
