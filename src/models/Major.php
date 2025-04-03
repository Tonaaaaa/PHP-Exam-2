<?php

class Major
{
    private $conn;
    private $table_name = "NganhHoc";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all majors
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY TenNganh";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Get major by ID
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE MaNganh = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new major
    public function create($maNganh, $tenNganh)
    {
        $query = "INSERT INTO " . $this->table_name . " SET MaNganh = :maNganh, TenNganh = :tenNganh";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind data
        $maNganh = htmlspecialchars(strip_tags($maNganh));
        $tenNganh = htmlspecialchars(strip_tags($tenNganh));

        $stmt->bindParam(":maNganh", $maNganh);
        $stmt->bindParam(":tenNganh", $tenNganh);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Update major
    public function update($maNganh, $tenNganh)
    {
        $query = "UPDATE " . $this->table_name . " SET TenNganh = :tenNganh WHERE MaNganh = :maNganh";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind data
        $maNganh = htmlspecialchars(strip_tags($maNganh));
        $tenNganh = htmlspecialchars(strip_tags($tenNganh));

        $stmt->bindParam(":maNganh", $maNganh);
        $stmt->bindParam(":tenNganh", $tenNganh);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete major
    public function delete($id)
    {
        // Kiểm tra xem có sinh viên nào thuộc ngành này không
        $query = "SELECT COUNT(*) as count FROM SinhVien WHERE MaNganh = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] > 0) {
            return false; // Không thể xóa vì có sinh viên thuộc ngành này
        }

        // Xóa ngành học
        $query = "DELETE FROM " . $this->table_name . " WHERE MaNganh = :id";

        $stmt = $this->conn->prepare($query);
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
