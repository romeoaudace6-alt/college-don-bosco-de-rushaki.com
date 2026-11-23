<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

if ($method === 'GET') {
    $result = $db->query("SELECT * FROM backgrounds");
    $data = [];
    while ($row = $result->fetch_assoc()) $data[$row['name']] = $row['image_path'];
    echo json_encode($data);
    exit;
}

if ($method === 'POST') {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $name = $_POST['name'] ?? '';
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = 'uploads/' . $filename;
        }
    }

    if ($image) {
        $stmt = $db->prepare("UPDATE backgrounds SET image_path=? WHERE name=?");
        $stmt->bind_param('ss', $image, $name);
        $stmt->execute();
    }
    echo json_encode(['success' => true]);
    exit;
}
?>