<?php

class Student
{
    private $conn;
    private $table_name = "SinhVien";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Tạo ID sinh viên tự động
    public function generateStudentId()
    {
        // Lấy năm hiện tại
        $year = date('Y');

        // Truy vấn ID sinh viên lớn nhất có cùng tiền tố năm
        $query = "SELECT MAX(MaSV) as max_id FROM " . $this->table_name . " WHERE MaSV LIKE :year_prefix";
        $stmt = $this->conn->prepare($query);
        $year_prefix = $year . '%';
        $stmt->bindParam(':year_prefix', $year_prefix);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['max_id']) {
            // Nếu đã có sinh viên với tiền tố năm này, tăng số thứ tự lên 1
            $last_id = $row['max_id'];
            $number = intval(substr($last_id, 4)) + 1;
        } else {
            // Nếu chưa có sinh viên với tiền tố năm này, bắt đầu từ 1
            $number = 1;
        }

        // Tạo ID mới với định dạng: YYYY + số thứ tự (5 chữ số)
        $new_id = $year . str_pad($number, 5, '0', STR_PAD_LEFT);

        return $new_id;
    }

    // Get all students with pagination
    public function getAll($page = 1, $limit = 4)
    {
        $offset = ($page - 1) * $limit;

        $query = "SELECT s.*, n.TenNganh 
                FROM " . $this->table_name . " s
                LEFT JOIN NganhHoc n ON s.MaNganh = n.MaNganh
                ORDER BY s.MaSV 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    // Get total number of students
    public function getTotalCount()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'];
    }

    // Get student by ID
    public function getById($id)
    {
        $query = "SELECT s.*, n.TenNganh 
                FROM " . $this->table_name . " s
                LEFT JOIN NganhHoc n ON s.MaNganh = n.MaNganh
                WHERE s.MaSV = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new student
    public function create($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh, $matKhau = '123456')
    {
        $query = "INSERT INTO " . $this->table_name . " 
                SET MaSV = :maSV, 
                    HoTen = :hoTen, 
                    GioiTinh = :gioiTinh, 
                    NgaySinh = :ngaySinh, 
                    Hinh = :hinh, 
                    MaNganh = :maNganh,
                    MatKhau = :matKhau";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind data
        $maSV = htmlspecialchars(strip_tags($maSV));
        $hoTen = htmlspecialchars(strip_tags($hoTen));
        $gioiTinh = htmlspecialchars(strip_tags($gioiTinh));
        $matKhau = password_hash($matKhau, PASSWORD_DEFAULT); // Mã hóa mật khẩu

        $stmt->bindParam(":maSV", $maSV);
        $stmt->bindParam(":hoTen", $hoTen);
        $stmt->bindParam(":gioiTinh", $gioiTinh);
        $stmt->bindParam(":ngaySinh", $ngaySinh);
        $stmt->bindParam(":hinh", $hinh);
        $stmt->bindParam(":maNganh", $maNganh);
        $stmt->bindParam(":matKhau", $matKhau);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Update student
    public function update($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh, $matKhau = null)
    {
        // Nếu mật khẩu được cung cấp, cập nhật cả mật khẩu
        if (!empty($matKhau)) {
            if (empty($hinh)) {
                $query = "UPDATE " . $this->table_name . " 
                        SET HoTen = :hoTen, 
                            GioiTinh = :gioiTinh, 
                            NgaySinh = :ngaySinh, 
                            MaNganh = :maNganh,
                            MatKhau = :matKhau 
                        WHERE MaSV = :maSV";
            } else {
                $query = "UPDATE " . $this->table_name . " 
                        SET HoTen = :hoTen, 
                            GioiTinh = :gioiTinh, 
                            NgaySinh = :ngaySinh, 
                            Hinh = :hinh, 
                            MaNganh = :maNganh,
                            MatKhau = :matKhau 
                        WHERE MaSV = :maSV";
            }
        } else {
            // Nếu không cập nhật mật khẩu
            if (empty($hinh)) {
                $query = "UPDATE " . $this->table_name . " 
                        SET HoTen = :hoTen, 
                            GioiTinh = :gioiTinh, 
                            NgaySinh = :ngaySinh, 
                            MaNganh = :maNganh 
                        WHERE MaSV = :maSV";
            } else {
                $query = "UPDATE " . $this->table_name . " 
                        SET HoTen = :hoTen, 
                            GioiTinh = :gioiTinh, 
                            NgaySinh = :ngaySinh, 
                            Hinh = :hinh, 
                            MaNganh = :maNganh 
                        WHERE MaSV = :maSV";
            }
        }

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind data
        $maSV = htmlspecialchars(strip_tags($maSV));
        $hoTen = htmlspecialchars(strip_tags($hoTen));
        $gioiTinh = htmlspecialchars(strip_tags($gioiTinh));

        $stmt->bindParam(":maSV", $maSV);
        $stmt->bindParam(":hoTen", $hoTen);
        $stmt->bindParam(":gioiTinh", $gioiTinh);
        $stmt->bindParam(":ngaySinh", $ngaySinh);
        $stmt->bindParam(":maNganh", $maNganh);

        if (!empty($hinh)) {
            $stmt->bindParam(":hinh", $hinh);
        }

        if (!empty($matKhau)) {
            $matKhau = password_hash($matKhau, PASSWORD_DEFAULT);
            $stmt->bindParam(":matKhau", $matKhau);
        }

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete student
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE MaSV = :id";

        $stmt = $this->conn->prepare($query);
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Validate student login
    public function validateLogin($maSV, $matKhau)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaSV = :maSV";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":maSV", $maSV);
        $stmt->execute();

        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra mật khẩu
        if ($student) {
            // Nếu mật khẩu chưa được mã hóa (trường hợp mật khẩu mặc định)
            if ($student['MatKhau'] === $matKhau) {
                return $student;
            }
            // Kiểm tra mật khẩu đã mã hóa
            else if (password_verify($matKhau, $student['MatKhau'])) {
                return $student;
            }
        }

        return false;
    }
}
