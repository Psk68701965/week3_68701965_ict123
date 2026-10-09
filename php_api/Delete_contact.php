<?php
include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['contact_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "ไม่ระบุรหัสผู้ติดต่อ"
    ]);
    exit;
}

try {
    $conn->exec("CREATE TABLE IF NOT EXISTS contacts (
        contact_id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        detail TEXT NOT NULL,
        email VARCHAR(255) NOT NULL
    )");

    $sql = "DELETE FROM contacts WHERE contact_id = :contact_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':contact_id' => $data['contact_id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => true,
            "message" => "ลบข้อมูลผู้ติดต่อเรียบร้อย"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "ไม่พบข้อมูลผู้ติดต่อที่ต้องการลบ"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
