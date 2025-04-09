<?php

require 'condb.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'GET') {
        // แสดงข้อมูลลูกค้าทั้งหมด
        $stmt = $conn->prepare("SELECT * FROM Department");
        $stmt->execute();
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $departments]);
    } elseif ($method == 'POST') {
        // เพิ่มข้อมูลลูกค้าใหม่
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['dept_id'], $data['dept_name'])) {
            $stmt = $conn->prepare("INSERT INTO department (dept_id, dept_name) VALUES (:dept_id, :dept_name)");
            $stmt->bindParam(':dept_id', $data['dept_id']);
            $stmt->bindParam(':dept_name', $data['dept_name']);
            $stmt->execute();
            echo json_encode(["success" => true, "message" => "Department added successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Missing required fields"]);
        }
    } elseif ($method == 'PUT') {
        // แก้ไขข้อมูลลูกค้า
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['dept_id'], $data['dept_name'])) {
            $stmt = $conn->prepare("UPDATE department SET dept_name = :dept_name WHERE dept_id = :dept_id");
            $stmt->bindParam(':dept_id', $data['dept_id']);
            $stmt->bindParam(':dept_name', $data['dept_name']);
            $stmt->execute();
            echo json_encode(["success" => true, "message" => "Department updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Missing required fields"]);
        }
    } elseif ($method == 'DELETE') {
        // ลบข้อมูลลูกค้า
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['dept_id'])) {
            $stmt = $conn->prepare("DELETE FROM department WHERE dept_id = :dept_id");
            $stmt->bindParam(':dept_id', $data['dept_id']);
            $stmt->execute();
            echo json_encode(["success" => true, "message" => "Department deleted successfully"]);
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
