<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Major.php';
require_once __DIR__ . '/../config/database.php';

class StudentController
{
    private $db;
    private $student;
    private $major;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->student = new Student($this->db);
        $this->major = new Major($this->db);
    }

    // Display list of students
    public function index()
    {
        // Get page number from URL
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 4; // 4 students per page

        // Get students
        $stmt = $this->student->getAll($page, $limit);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get total number of students
        $total_students = $this->student->getTotalCount();
        $total_pages = ceil($total_students / $limit);

        // Include view
        include __DIR__ . '/../views/student/index.php';
    }

    // Display form to create a new student
    public function create()
    {
        // Get all majors
        $stmt = $this->major->getAll();
        $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Include view
        include __DIR__ . '/../views/student/create.php';
    }

    // Store new student
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Tự động tạo ID sinh viên
            $maSV = $this->student->generateStudentId();

            // Lấy dữ liệu từ form
            $hoTen = trim($_POST['hoTen'] ?? '');
            $gioiTinh = trim($_POST['gioiTinh'] ?? '');
            $ngaySinh = trim($_POST['ngaySinh'] ?? '');
            $maNganh = trim($_POST['maNganh'] ?? '');
            $matKhau = password_hash('123456', PASSWORD_DEFAULT); // Mật khẩu mặc định được mã hóa

            // Kiểm tra dữ liệu đầu vào
            if (empty($hoTen) || empty($gioiTinh) || empty($ngaySinh) || empty($maNganh)) {
                $error = "Vui lòng điền đầy đủ thông tin bắt buộc.";
                $stmt = $this->major->getAll();
                $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                include __DIR__ . '/../views/student/create.php';
                exit;
            }

            // Xử lý hình ảnh
            $hinh = '';
            $target_dir = __DIR__ . '/../../public/assets/images/'; // Đường dẫn tuyệt đối tới thư mục công khai

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $file_type = mime_content_type($_FILES['hinh']['tmp_name']);
                $file_size = $_FILES['hinh']['size'];
                $max_size = 5 * 1024 * 1024; // 5MB

                if (!in_array($file_type, $allowed_types)) {
                    $error = "Chỉ chấp nhận file ảnh (JPEG, PNG, GIF).";
                } elseif ($file_size > $max_size) {
                    $error = "Kích thước file không được vượt quá 5MB.";
                } else {
                    $file_name = time() . '_' . basename($_FILES["hinh"]["name"]);
                    $target_file = $target_dir . $file_name;
                    $relative_path = 'assets/images/' . $file_name; // Đường dẫn tương đối lưu vào DB

                    if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
                        $hinh = $relative_path;
                    } else {
                        $error = "Không thể tải lên hình ảnh. Vui lòng kiểm tra quyền thư mục.";
                    }
                }
            }

            // Nếu có lỗi, hiển thị lại form
            if (isset($error)) {
                $stmt = $this->major->getAll();
                $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                include __DIR__ . '/../views/student/create.php';
                exit;
            }

            // Tạo sinh viên
            if ($this->student->create($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh, $matKhau)) {
                $_SESSION['success'] = "Sinh viên đã được thêm thành công với mã: " . $maSV;
                header('Location: index.php?controller=student&action=index');
                exit;
            } else {
                $error = "Không thể tạo sinh viên mới.";
                $stmt = $this->major->getAll();
                $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                include __DIR__ . '/../views/student/create.php';
            }
        }
    }

    // Display form to edit a student
    public function edit()
    {
        // Get student ID from URL
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

        // Get student data
        $student = $this->student->getById($id);

        // Get all majors
        $stmt = $this->major->getAll();
        $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Include view
        include __DIR__ . '/../views/student/edit.php';
    }

    // Update student
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $maSV = $_POST['maSV'];
            $hoTen = $_POST['hoTen'];
            $gioiTinh = $_POST['gioiTinh'];
            $ngaySinh = $_POST['ngaySinh'];
            $maNganh = $_POST['maNganh'];
            $matKhau = !empty($_POST['matKhau']) ? password_hash($_POST['matKhau'], PASSWORD_DEFAULT) : null;

            // Handle image upload
            $hinh = '';
            $target_dir = __DIR__ . '/../../public/assets/images/';

            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $file_type = mime_content_type($_FILES['hinh']['tmp_name']);
                $file_size = $_FILES['hinh']['size'];
                $max_size = 5 * 1024 * 1024; // 5MB

                if (!in_array($file_type, $allowed_types)) {
                    $error = "Chỉ chấp nhận file ảnh (JPEG, PNG, GIF).";
                } elseif ($file_size > $max_size) {
                    $error = "Kích thước file không được vượt quá 5MB.";
                } else {
                    $file_name = time() . '_' . basename($_FILES["hinh"]["name"]);
                    $target_file = $target_dir . $file_name;
                    $relative_path = 'assets/images/' . $file_name;

                    if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
                        $hinh = $relative_path;

                        // Delete old image
                        $student = $this->student->getById($maSV);
                        if (!empty($student['Hinh']) && file_exists(__DIR__ . '/../../public/' . $student['Hinh'])) {
                            unlink(__DIR__ . '/../../public/' . $student['Hinh']);
                        }
                    } else {
                        $error = "Không thể tải lên hình ảnh.";
                    }
                }
            }

            // If there's an error, show the form again
            if (isset($error)) {
                $student = $this->student->getById($maSV);
                $stmt = $this->major->getAll();
                $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                include __DIR__ . '/../views/student/edit.php';
                exit;
            }

            // Update student
            if ($this->student->update($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh, $matKhau)) {
                $_SESSION['success'] = "Thông tin sinh viên đã được cập nhật thành công.";
                header('Location: index.php?controller=student&action=index');
                exit;
            } else {
                $error = "Không thể cập nhật thông tin sinh viên.";
                $student = $this->student->getById($maSV);
                $stmt = $this->major->getAll();
                $majors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                include __DIR__ . '/../views/student/edit.php';
            }
        }
    }

    // Display student details
    public function show()
    {
        // Get student ID from URL
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

        // Get student data
        $student = $this->student->getById($id);

        // Include view
        include __DIR__ . '/../views/student/detail.php';
    }

    // Delete student
    public function delete()
    {
        // Get student ID from URL
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

        // Get student data to delete image
        $student = $this->student->getById($id);

        // Delete student
        if ($this->student->delete($id)) {
            // Delete student image
            if (!empty($student['Hinh']) && file_exists(__DIR__ . '/../../public/' . $student['Hinh'])) {
                unlink(__DIR__ . '/../../public/' . $student['Hinh']);
            }

            $_SESSION['success'] = "Sinh viên đã được xóa thành công.";
            header('Location: index.php?controller=student&action=index');
            exit;
        } else {
            $_SESSION['error'] = "Không thể xóa sinh viên.";
            header('Location: index.php?controller=student&action=index');
            exit;
        }
    }
}
