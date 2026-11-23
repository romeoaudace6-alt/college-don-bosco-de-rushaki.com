<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

if ($method === 'GET') {
    // Kubona amakuru yose y'ikigo
    $result = $db->query("SELECT * FROM settings WHERE id = 1");
    $settings = $result->fetch_assoc();
    echo json_encode($settings);
    exit;
}

if ($method === 'POST') {
    // Kureba niba ari admin cyangwa editor winjiye
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'editor'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Fata amakuru ava kuri form
    $school_name = $_POST['school_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $working_hours = $_POST['working_hours'] ?? '';
    $hero_title = $_POST['hero_title'] ?? '';
    $hero_subtitle = $_POST['hero_subtitle'] ?? '';
    $hero_badge = $_POST['hero_badge'] ?? '';
    $about_text = $_POST['about_text'] ?? '';

    // Bika mu database
    $stmt = $db->prepare("UPDATE settings SET school_name=?, phone=?, email=?, address=?, working_hours=?, hero_title=?, hero_subtitle=?, hero_badge=?, about_text=? WHERE id=1");
    $stmt->bind_param('sssssssss', $school_name, $phone, $email, $address, $working_hours, $hero_title, $hero_subtitle, $hero_badge, $about_text);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Settings updated']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update settings']);
    }
    exit;
}
?>