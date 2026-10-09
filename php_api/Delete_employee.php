<?php
include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['emp_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "ไม่ระบุรหัสพนักงาน"
    ]);
    exit;
}

try {
    $sql = "DELETE FROM employees WHERE emp_id = :emp_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':emp_id' => $data['emp_id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => true,
            "message" => "ลบข้อมูลพนักงานเรียบร้อย"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "ไม่พบข้อมูลพนักงานที่ต้องการลบ"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
