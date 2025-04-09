<?php

require 'condb.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'GET') {
        // แสดงข้อมูลลูกค้าทั้งหมด
        $stmt = $conn->prepare("SELECT * FROM Customers");
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $customers]);
    } elseif ($method == 'POST') {
        // เพิ่มข้อมูลลูกค้าใหม่
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['FirstName'], $data['LastName'], $data['PhoneNumber'], $data['Username'], $data['Password'])) {
            $stmt = $conn->prepare("INSERT INTO Customers (FirstName, LastName, PhoneNumber, Username, Password) VALUES (:FirstName, :LastName, :PhoneNumber, :Username, :Password)");
            $stmt->bindParam(':FirstName', $data['FirstName']);
            $stmt->bindParam(':LastName', $data['LastName']);
            $stmt->bindParam(':PhoneNumber', $data['PhoneNumber']);
            $stmt->bindParam(':Username', $data['Username']);
            $hashedPassword = password_hash($data['Password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':Password', $hashedPassword);
            $stmt->execute();
            echo json_encode(["success" => true, "message" => "Customer added successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Missing required fields"]);
        }
    } elseif ($method == 'PUT') {
        // แก้ไขข้อมูลลูกค้า
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['CustomerID'], $data['FirstName'], $data['LastName'], $data['PhoneNumber'], $data['Username'])) {
            $stmt = $conn->prepare("UPDATE Customers SET FirstName = :FirstName, LastName = :LastName, PhoneNumber = :PhoneNumber, Username = :Username WHERE CustomerID = :CustomerID");
            $stmt->bindParam(':FirstName', $data['FirstName']);
            $stmt->bindParam(':LastName', $data['LastName']);
            $stmt->bindParam(':PhoneNumber', $data['PhoneNumber']);
            $stmt->bindParam(':Username', $data['Username']);
            $stmt->bindParam(':CustomerID', $data['CustomerID']);
            $stmt->execute();
            echo json_encode(["success" => true, "message" => "Customer updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Missing required fields"]);
        }
    } elseif ($method == 'DELETE') {
        // ลบข้อมูลลูกค้า
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['CustomerID'])) {
            $stmt = $conn->prepare("DELETE FROM Customers WHERE CustomerID = :CustomerID");
            $stmt->bindParam(':CustomerID', $data['CustomerID']);
            $stmt->execute();
            echo json_encode(["success" => true, "message" => "Customer deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Missing CustomerID"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request method"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
