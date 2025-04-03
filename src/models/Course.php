<?php
class Course
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Lấy tất cả học phần và tính số lượng còn lại
    public function getAll()
    {
        $query = "SELECT 
                    h.MaHP AS MaHP,
                    h.TenHP AS TenHP,
                    h.SoTinChi AS SoTinChi,
                    h.SoLuongMax AS SoLuongMax,
                    (h.SoLuongMax - COALESCE(COUNT(ct.MaHP), 0)) AS SoLuongSV
                  FROM HocPhan h
                  LEFT JOIN ChiTietDangKy ct ON h.MaHP = ct.MaHP
                  GROUP BY h.MaHP, h.TenHP, h.SoTinChi, h.SoLuongMax";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Kiểm tra xem sinh viên đã đăng ký học phần chưa
    public function isRegisteredByStudent($maHP, $maSV)
    {
        $query = "SELECT COUNT(*) 
                  FROM ChiTietDangKy ct
                  JOIN DangKy dk ON ct.MaDK = dk.MaDK
                  WHERE ct.MaHP = :maHP AND dk.MaSV = :maSV";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maHP', $maHP);
        $stmt->bindParam(':maSV', $maSV);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Kiểm tra xem học phần còn chỗ không
    public function hasAvailableSlots($maHP)
    {
        $query = "SELECT (h.SoLuongMax - COALESCE(COUNT(ct.MaHP), 0)) AS SoLuongSV
                  FROM HocPhan h
                  LEFT JOIN ChiTietDangKy ct ON h.MaHP = ct.MaHP
                  WHERE h.MaHP = :maHP
                  GROUP BY h.MaHP, h.SoLuongMax";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maHP', $maHP);
        $stmt->execute();
        $remaining = $stmt->fetchColumn();
        return $remaining > 0;
    }

    // Lấy thông tin học phần theo ID
    public function getById($maHP)
    {
        $query = "SELECT * FROM HocPhan WHERE MaHP = :maHP";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maHP', $maHP);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mới một học phần
    public function create($maHP, $tenHP, $soTinChi, $soLuongMax)
    {
        $query = "INSERT INTO HocPhan (MaHP, TenHP, SoTinChi, SoLuongMax) 
                  VALUES (:maHP, :tenHP, :soTinChi, :soLuongMax)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maHP', $maHP);
        $stmt->bindParam(':tenHP', $tenHP);
        $stmt->bindParam(':soTinChi', $soTinChi);
        $stmt->bindParam(':soLuongMax', $soLuongMax);
        return $stmt->execute();
    }

    // Cập nhật thông tin học phần
    public function update($maHP, $tenHP, $soTinChi, $soLuongMax)
    {
        $query = "UPDATE HocPhan 
                  SET TenHP = :tenHP, SoTinChi = :soTinChi, SoLuongMax = :soLuongMax 
                  WHERE MaHP = :maHP";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maHP', $maHP);
        $stmt->bindParam(':tenHP', $tenHP);
        $stmt->bindParam(':soTinChi', $soTinChi);
        $stmt->bindParam(':soLuongMax', $soLuongMax);
        return $stmt->execute();
    }

    // Xóa học phần
    public function delete($maHP)
    {
        $query = "DELETE FROM HocPhan WHERE MaHP = :maHP";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maHP', $maHP);
        return $stmt->execute();
    }
}
