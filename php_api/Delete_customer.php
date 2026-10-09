<?php
include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['customer_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "ไม่ระบุรหัสลูกค้า"
    ]);
    exit;
}

try {
    $sql = "DELETE FROM customers WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':customer_id' => $data['customer_id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => true,
            "message" => "ลบข้อมูลลูกค้าเรียบร้อย"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "ไม่พบข้อมูลลูกค้าที่ต้องการลบ"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
