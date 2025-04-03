<?php
class Admin
{
    private $conn;
    private $table_name = "Admins";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function validateLogin($username, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['Password'])) {
            return $admin;
        }
        return false;
    }
}
