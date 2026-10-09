<?php
include 'condb.php';
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $body = file_get_contents("php://input");
    $payload = $body !== '' ? json_decode($body, true) : [];
    $action = $payload['action'] ?? null;

    if ($method === 'GET') {
        $action = $_GET['action'] ?? 'read';
    }

    if ($method === 'GET' || $action === 'read') {
        $stmt = $conn->prepare("SELECT * FROM employees ORDER BY emp_id DESC");
        $stmt->execute();
        echo json_encode(["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit;
    }

    if ($method === 'POST' || $action === 'create') {
        $data = $payload;

        if (empty($data["firstName"]) || empty($data["lastName"]) || empty($data["phone"]) || empty($data["username"]) || empty($data["password"])) {
            echo json_encode(["success" => false, "message" => "กรุณากรอกข้อมูลให้ครบ"]);
            exit;
        }

        $password_hash = password_hash($data["password"], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO employees (firstName, lastName, phone, username, password)
                                VALUES (:firstName, :lastName, :phone, :username, :password)");

        $stmt->bindParam(":firstName", $data["firstName"]);
        $stmt->bindParam(":lastName", $data["lastName"]);
        $stmt->bindParam(":phone", $data["phone"]);
        $stmt->bindParam(":username", $data["username"]);
        $stmt->bindParam(":password", $password_hash);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "เพิ่มข้อมูลพนักงานเรียบร้อย"]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถเพิ่มข้อมูลพนักงานได้"]);
        }
        exit;
    }

    if ($method === 'PUT' || $action === 'update') {
        $data = $payload;

        if (!isset($data["emp_id"])) {
            echo json_encode(["success" => false, "message" => "ไม่พบค่า emp_id"]);
            exit;
        }

        $emp_id = intval($data["emp_id"]);

        if (!empty($data["password"])) {
            $password_hash = password_hash($data["password"], PASSWORD_BCRYPT);
            $sql = "UPDATE employees 
                    SET firstName = :firstName, 
                        lastName = :lastName, 
                        phone = :phone, 
                        username = :username,
                        password = :password
                    WHERE emp_id = :id";
        } else {
            $sql = "UPDATE employees 
                    SET firstName = :firstName, 
                        lastName = :lastName, 
                        phone = :phone, 
                        username = :username
                    WHERE emp_id = :id";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":firstName", $data["firstName"]);
        $stmt->bindParam(":lastName", $data["lastName"]);
        $stmt->bindParam(":phone", $data["phone"]);
        $stmt->bindParam(":username", $data["username"]);
        if (!empty($data["password"])) {
            $stmt->bindParam(":password", $password_hash);
        }
        $stmt->bindParam(":id", $emp_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "แก้ไขข้อมูลพนักงานเรียบร้อย"]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถแก้ไขข้อมูลพนักงานได้"]);
        }
        exit;
    }

    if ($method === 'DELETE' || $action === 'delete') {
        $data = $payload;

        if (!isset($data["emp_id"])) {
            echo json_encode(["success" => false, "message" => "ไม่พบค่า emp_id"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM employees WHERE emp_id = :id");
        $stmt->bindParam(":id", $data["emp_id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "ลบข้อมูลพนักงานเรียบร้อย"]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถลบข้อมูลพนักงานได้"]);
        }
        exit;
    }

    echo json_encode(["success" => false, "message" => "Action ไม่ถูกต้อง"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
