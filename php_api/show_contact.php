<?php
include 'condb.php';

try {
    $conn->exec("CREATE TABLE IF NOT EXISTS contacts (
        contact_id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        detail TEXT NOT NULL,
        email VARCHAR(255) NOT NULL
    )");

    $stmt = $conn->query("SELECT * FROM contacts ORDER BY contact_id DESC");
    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($datas);

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
