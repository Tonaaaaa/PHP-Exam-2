<?php
require_once __DIR__ . '/../models/Registration.php';
require_once __DIR__ . '/../models/RegistrationDetail.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../config/database.php';

class RegistrationController
{
    private $db;
    private $registration;
    private $registrationDetail;
    private $course;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->registration = new Registration($this->db);
        $this->registrationDetail = new RegistrationDetail($this->db);
        $this->course = new Course($this->db);
    }

    public function index()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        $stmt = $this->registration->getByStudentId($_SESSION['student_id']);
        $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../views/registration/index.php';
    }

    public function store()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maHP = $_POST['maHP'];

            if ($this->course->isRegisteredByStudent($maHP, $_SESSION['student_id'])) {
                $_SESSION['error'] = "Bạn đã đăng ký học phần này rồi.";
                header('Location: index.php?controller=course&action=index');
                exit;
            }

            if (!$this->course->hasAvailableSlots($maHP)) {
                $_SESSION['error'] = "Học phần này đã đủ số lượng sinh viên.";
                header('Location: index.php?controller=course&action=index');
                exit;
            }

            $this->db->beginTransaction();
            try {
                $maDK = $this->registration->create($_SESSION['student_id']);
                if (!$maDK) {
                    throw new Exception("Không thể tạo đăng ký.");
                }

                if (!$this->registrationDetail->create($maDK, $maHP)) {
                    throw new Exception("Không thể tạo chi tiết đăng ký.");
                }

                $this->db->commit();
                header('Location: index.php?controller=registration&action=success');
                exit;
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = $e->getMessage();
                header('Location: index.php?controller=course&action=index');
                exit;
            }
        }
    }

    public function success()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        include __DIR__ . '/../views/registration/success.php';
    }

    public function cancelCourse()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $maDK = isset($_GET['maDK']) ? $_GET['maDK'] : die('ERROR: Missing Registration ID.');
        $maHP = isset($_GET['maHP']) ? $_GET['maHP'] : die('ERROR: Missing Course ID.');

        $registration = $this->registration->getById($maDK);
        if ($registration['MaSV'] != $_SESSION['student_id']) {
            $_SESSION['error'] = "Bạn không có quyền hủy đăng ký này.";
            header('Location: index.php?controller=registration&action=index');
            exit;
        }

        $this->db->beginTransaction();
        try {
            if (!$this->registrationDetail->delete($maDK, $maHP)) {
                throw new Exception("Không thể hủy đăng ký học phần.");
            }

            $this->db->commit();
            $_SESSION['success'] = "Đã hủy đăng ký học phần thành công.";
            header('Location: index.php?controller=registration&action=index');
            exit;
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?controller=registration&action=index');
            exit;
        }
    }

    public function cancelAll()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $stmt = $this->registration->getByStudentId($_SESSION['student_id']);
        $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->db->beginTransaction();
        try {
            foreach ($registrations as $registration) {
                $maDK = $registration['MaDK'];
                $maHP = $registration['MaHP'];
                if (!$this->registrationDetail->delete($maDK, $maHP)) {
                    throw new Exception("Không thể hủy đăng ký học phần.");
                }
            }

            $this->db->commit();
            $_SESSION['success'] = "Đã hủy tất cả đăng ký học phần thành công.";
            header('Location: index.php?controller=registration&action=index');
            exit;
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?controller=registration&action=index');
            exit;
        }
    }
}
