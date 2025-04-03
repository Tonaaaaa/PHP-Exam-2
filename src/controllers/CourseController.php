<?php
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../config/database.php';

class CourseController
{
    private $db;
    private $course;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->course = new Course($this->db);
    }

    public function index()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        $stmt = $this->course->getAll();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($courses as &$course) {
            $course['isRegistered'] = $this->course->isRegisteredByStudent($course['MaHP'], $_SESSION['student_id']);
        }
        include __DIR__ . '/../views/course/index.php';
    }

    public function register()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        if ($this->course->isRegisteredByStudent($id, $_SESSION['student_id'])) {
            $_SESSION['error'] = "Bạn đã đăng ký học phần này rồi.";
            header('Location: index.php?controller=course&action=index');
            exit;
        }
        if (!$this->course->hasAvailableSlots($id)) {
            $_SESSION['error'] = "Học phần này đã đủ số lượng sinh viên.";
            header('Location: index.php?controller=course&action=index');
            exit;
        }
        $course = $this->course->getById($id);
        include __DIR__ . '/../views/course/register.php';
    }

    public function manage()
    {
        $stmt = $this->course->getAll();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../views/course/manage.php';
    }

    public function create()
    {
        include __DIR__ . '/../views/course/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maHP = $_POST['maHP'];
            $tenHP = $_POST['tenHP'];
            $soTinChi = $_POST['soTinChi'];
            $soLuongMax = $_POST['soLuongMax']; // Thay $soLuongSV thành $soLuongMax

            if ($this->course->create($maHP, $tenHP, $soTinChi, $soLuongMax)) {
                $_SESSION['success'] = "Học phần đã được thêm thành công.";
                header('Location: index.php?controller=course&action=manage');
                exit;
            } else {
                $error = "Không thể tạo học phần mới.";
                include __DIR__ . '/../views/course/create.php';
            }
        }
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        $course = $this->course->getById($id);
        include __DIR__ . '/../views/course/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maHP = $_POST['maHP'];
            $tenHP = $_POST['tenHP'];
            $soTinChi = $_POST['soTinChi'];
            $soLuongMax = $_POST['soLuongMax']; // Thay $soLuongSV thành $soLuongMax

            if ($this->course->update($maHP, $tenHP, $soTinChi, $soLuongMax)) {
                $_SESSION['success'] = "Học phần đã được cập nhật thành công.";
                header('Location: index.php?controller=course&action=manage');
                exit;
            } else {
                $error = "Không thể cập nhật học phần.";
                $course = $this->course->getById($maHP);
                include __DIR__ . '/../views/course/edit.php';
            }
        }
    }

    public function delete()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
        if ($this->course->delete($id)) {
            $_SESSION['success'] = "Học phần đã được xóa thành công.";
            header('Location: index.php?controller=course&action=manage');
            exit;
        } else {
            $_SESSION['error'] = "Không thể xóa học phần.";
            header('Location: index.php?controller=course&action=manage');
            exit;
        }
    }
}
