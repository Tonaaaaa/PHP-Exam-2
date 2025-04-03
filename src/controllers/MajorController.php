<?php

require_once __DIR__ . '/../models/Major.php';
require_once __DIR__ . '/../config/database.php';

class MajorController
{
    private $db;
    private $major;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->major = new Major($this->db);
    }

    // Hiển thị danh sách ngành học
    public function index()
    {
        // Lấy tất cả ngành học
        $stmt = $this->major->getAll();
        $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Include view
        include __DIR__ . '/../views/major/index.php';
    }

    // Hiển thị form tạo ngành học mới
    public function create()
    {
        // Include view
        include __DIR__ . '/../views/major/create.php';
    }

    // Lưu ngành học mới
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu form
            $maNganh = $_POST['maNganh'];
            $tenNganh = $_POST['tenNganh'];

            // Tạo ngành học mới
            if ($this->major->create($maNganh, $tenNganh)) {
                // Thông báo thành công
                $_SESSION['success'] = "Ngành học đã được thêm thành công.";

                // Chuyển hướng đến danh sách ngành học
                header('Location: index.php?controller=major&action=index');
                exit;
            } else {
                // Thông báo lỗi
                $error = "Không thể tạo ngành học mới.";

                // Include view
                include __DIR__ . '/../views/major/create.php';
            }
        }
    }

    // Hiển thị form chỉnh sửa ngành học
    public function edit()
    {
        // Lấy ID ngành học từ URL
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

        // Lấy dữ liệu ngành học
        $major = $this->major->getById($id);

        // Include view
        include __DIR__ . '/../views/major/edit.php';
    }

    // Cập nhật ngành học
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu form
            $maNganh = $_POST['maNganh'];
            $tenNganh = $_POST['tenNganh'];

            // Cập nhật ngành học
            if ($this->major->update($maNganh, $tenNganh)) {
                // Thông báo thành công
                $_SESSION['success'] = "Ngành học đã được cập nhật thành công.";

                // Chuyển hướng đến danh sách ngành học
                header('Location: index.php?controller=major&action=index');
                exit;
            } else {
                // Thông báo lỗi
                $error = "Không thể cập nhật ngành học.";

                // Lấy dữ liệu ngành học
                $major = $this->major->getById($maNganh);

                // Include view
                include __DIR__ . '/../views/major/edit.php';
            }
        }
    }

    // Xóa ngành học
    public function delete()
    {
        // Lấy ID ngành học từ URL
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

        // Xóa ngành học
        if ($this->major->delete($id)) {
            // Thông báo thành công
            $_SESSION['success'] = "Ngành học đã được xóa thành công.";

            // Chuyển hướng đến danh sách ngành học
            header('Location: index.php?controller=major&action=index');
            exit;
        } else {
            // Thông báo lỗi
            $_SESSION['error'] = "Không thể xóa ngành học.";
            header('Location: index.php?controller=major&action=index');
            exit;
        }
    }
}
