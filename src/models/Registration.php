<?php
class Registration
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($maSV)
    {
        $query = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (CURDATE(), :maSV)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maSV', $maSV);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getByStudentId($maSV)
    {
        $query = "SELECT 
                    dk.MaDK,
                    dk.NgayDK,
                    dk.MaSV,
                    ct.MaHP,
                    hp.TenHP,
                    hp.SoTinChi
                  FROM DangKy dk 
                  LEFT JOIN ChiTietDangKy ct ON dk.MaDK = ct.MaDK 
                  LEFT JOIN HocPhan hp ON ct.MaHP = hp.MaHP
                  WHERE dk.MaSV = :maSV";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maSV', $maSV);
        $stmt->execute();
        return $stmt;
    }

    public function getById($maDK)
    {
        $query = "SELECT * FROM DangKy WHERE MaDK = :maDK";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maDK', $maDK);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
