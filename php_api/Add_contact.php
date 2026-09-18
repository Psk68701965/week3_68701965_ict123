<?php

include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['fullname']) ||
    !isset($data['subject']) ||
    !isset($data['detail']) ||
    !isset($data['email'])
) {
    echo json_encode([
        "success" => false,
        "message" => "ข้อมูลไม่ครบ"
    ]);
    exit;
}

try {
    $sql = "INSERT INTO contacts
            (fullname,subject, detail, email)
            VALUES
            (:fullname, :subject, :detail, :email)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':fullname' => $data['fullname'],
        ':subject' => $data['subject'],
        ':detail'  => $data['detail'],
        ':email'   => $data['email']
    ]);

    echo json_encode([
        "success" => true,
        "message" => "เพิ่มข้อมูลเรียบร้อย"
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
