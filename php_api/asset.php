<?php

header("Content-Type: application/json; charset=utf-8");
ini_set('display_errors', '0');

try {
include 'condb.php';

$action = $_POST['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action) {
    switch ($action) {
        case 'add':
            $asset_code = $_POST['asset_code'] ?? '';
            $asset_name = $_POST['asset_name'];
            $purchase_date = $_POST['purchase_date'];
            $price = $_POST['price'];

            $filename = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir = 'uploads/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $filename = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            }

                $sql = "INSERT INTO assets (asset_code, asset_name, purchase_date, price, image)
                    VALUES (:asset_code, :asset_name, :purchase_date, :price, :image)";
            $stmt = $conn->prepare($sql);
                $stmt->bindParam(':asset_code', $asset_code);
            $stmt->bindParam(':asset_name', $asset_name);
                $stmt->bindParam(':purchase_date', $purchase_date);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':image', $filename);

            if ($stmt->execute()) {
                echo json_encode(["message" => "เพิ่มทรัพย์สินสำเร็จ"]);
            } else {
                echo json_encode(["error" => "เพิ่มทรัพย์สินล้มเหลว"]);
            }
            break;

        case 'update':
            $asset_id = $_POST['asset_id'];
            $asset_code = $_POST['asset_code'] ?? '';
            $asset_name = $_POST['asset_name'];
            $purchase_date = $_POST['purchase_date'];
            $price = $_POST['price'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir = 'uploads/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $filename = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
                $imageSQL = ", image = :image";
            } else {
                $imageSQL = "";
            }

            $sql = "UPDATE assets SET
                        asset_code = :asset_code,
                        asset_name = :asset_name,
                        purchase_date = :purchase_date,
                        price = :price$imageSQL
                    WHERE asset_id = :asset_id";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':asset_code', $asset_code);
            $stmt->bindParam(':asset_name', $asset_name);
            $stmt->bindParam(':purchase_date', $purchase_date);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':asset_id', $asset_id);
            if (isset($filename)) $stmt->bindParam(':image', $filename);

            if ($stmt->execute()) {
                echo json_encode(["message" => "แก้ไขทรัพย์สินสำเร็จ"]);
            } else {
                echo json_encode(["error" => "แก้ไขทรัพย์สินล้มเหลว"]);
            }
            break;

        case 'delete':
            $asset_id = $_POST['asset_id'];
            $stmt = $conn->prepare("DELETE FROM assets WHERE asset_id = :asset_id");
            $stmt->bindParam(':asset_id', $asset_id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "ลบทรัพย์สินสำเร็จ"]);
            } else {
                echo json_encode(["error" => "ลบทรัพย์สินล้มเหลว"]);
            }
            break;

        default:
            echo json_encode(["error" => "Action ไม่ถูกต้อง"]);
            break;
    }

} else {
    $stmt = $conn->prepare("SELECT * FROM assets ORDER BY asset_id DESC");
    if ($stmt->execute()) {
        $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $assets]);
    } else {
        echo json_encode(["success" => false, "data" => []]);
    }
}
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "ไม่สามารถอ่านหรือบันทึกข้อมูลทรัพย์สินได้",
        "details" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
