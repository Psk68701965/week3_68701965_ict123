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

$conn->exec("CREATE TABLE IF NOT EXISTS contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    detail TEXT NOT NULL,
    email VARCHAR(255) NOT NULL
)");

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $body = file_get_contents("php://input");
    $payload = $body !== '' ? json_decode($body, true) : [];
    $action = $payload['action'] ?? null;

    if ($method === 'GET') {
        $action = $_GET['action'] ?? 'read';
    }

    if ($method === 'GET' || $action === 'read') {
        $stmt = $conn->prepare("SELECT * FROM contacts ORDER BY contact_id DESC");
        $stmt->execute();
        echo json_encode([
            "success" => true,
            "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
        exit;
    }

    if ($method === 'POST' || $action === 'create') {
        $data = $payload;

        if (
            empty($data["fullname"]) ||
            empty($data["subject"]) ||
            empty($data["detail"]) ||
            empty($data["email"])
        ) {
            echo json_encode(["success" => false, "message" => "กรุณากรอกข้อมูลให้ครบ"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO contacts (fullname, subject, detail, email)
                                VALUES (:fullname, :subject, :detail, :email)");

        $stmt->bindParam(":fullname", $data["fullname"]);
        $stmt->bindParam(":subject", $data["subject"]);
        $stmt->bindParam(":detail", $data["detail"]);
        $stmt->bindParam(":email", $data["email"]);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "เพิ่มข้อมูลผู้ติดต่อเรียบร้อย"]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถเพิ่มข้อมูลผู้ติดต่อได้"]);
        }
        exit;
    }

    if ($method === 'PUT' || $action === 'update') {
        $data = $payload;

        if (!isset($data["contact_id"])) {
            echo json_encode(["success" => false, "message" => "ไม่พบค่า contact_id"]);
            exit;
        }

        $contact_id = intval($data["contact_id"]);

        $sql = "UPDATE contacts
                SET fullname = :fullname,
                    subject = :subject,
                    detail = :detail,
                    email = :email
                WHERE contact_id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":fullname", $data["fullname"]);
        $stmt->bindParam(":subject", $data["subject"]);
        $stmt->bindParam(":detail", $data["detail"]);
        $stmt->bindParam(":email", $data["email"]);
        $stmt->bindParam(":id", $contact_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "แก้ไขข้อมูลผู้ติดต่อเรียบร้อย"]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถแก้ไขข้อมูลผู้ติดต่อได้"]);
        }
        exit;
    }

    if ($method === 'DELETE' || $action === 'delete') {
        $data = $payload;

        if (!isset($data["contact_id"])) {
            echo json_encode(["success" => false, "message" => "ไม่พบค่า contact_id"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM contacts WHERE contact_id = :id");
        $stmt->bindParam(":id", $data["contact_id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "ลบข้อมูลผู้ติดต่อเรียบร้อย"]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถลบข้อมูลผู้ติดต่อได้"]);
        }
        exit;
    }

    echo json_encode(["success" => false, "message" => "Action ไม่ถูกต้อง"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
