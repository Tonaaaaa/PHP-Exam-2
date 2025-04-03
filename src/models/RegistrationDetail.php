<?php
class RegistrationDetail
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($maDK, $maHP)
    {
        $query = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (:maDK, :maHP)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maDK', $maDK);
        $stmt->bindParam(':maHP', $maHP);
        return $stmt->execute();
    }

    public function delete($maDK, $maHP)
    {
        $query = "DELETE FROM ChiTietDangKy WHERE MaDK = :maDK AND MaHP = :maHP";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maDK', $maDK);
        $stmt->bindParam(':maHP', $maHP);
        return $stmt->execute();
    }
}
