<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();
$action = $_GET['action'] ?? '';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($action === 'stats') {
    $staff = $db->query("SELECT COUNT(*) as c FROM staff")->fetch_assoc()['c'];
    $events = $db->query("SELECT COUNT(*) as c FROM events")->fetch_assoc()['c'];
    $news = $db->query("SELECT COUNT(*) as c FROM news")->fetch_assoc()['c'];
    $gallery = $db->query("SELECT COUNT(*) as c FROM gallery")->fetch_assoc()['c'];
    echo json_encode(['staff' => $staff, 'events' => $events, 'news' => $news, 'gallery' => $gallery]);
    exit;
}

if ($action === 'get_staff') {
    $result = $db->query("SELECT * FROM staff ORDER BY id DESC");
    $data = [];
    while ($row = $result->fetch_assoc()) $data[] = $row;
    echo json_encode($data);
    exit;
}

if ($action === 'add_staff' && $method === 'POST') {
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = uniqid() . '_' . basename($_FILES['photo']['name']);
        $target = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) $photo = 'uploads/' . $filename;
    }
    $stmt = $db->prepare("INSERT INTO staff (full_name, position, category, photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $_POST['full_name'], $_POST['position'], $_POST['category'], $photo);
    echo json_encode(['success' => $stmt->execute()]);
    exit;
}

if ($action === 'delete_staff' && $method === 'POST') {
    $stmt = $db->prepare("DELETE FROM staff WHERE id = ?");
    $stmt->bind_param('i', $_POST['id']);
    echo json_encode(['success' => $stmt->execute()]);
    exit;
}

if ($action === 'get_events') {
    $result = $db->query("SELECT * FROM events ORDER BY event_date DESC");
    $data = [];
    while ($row = $result->fetch_assoc()) $data[] = $row;
    echo json_encode($data);
    exit;
}

if ($action === 'add_event' && $method === 'POST') {
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) $image = 'uploads/' . $filename;
    }
    $stmt = $db->prepare("INSERT INTO events (title, event_date, location, description, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $_POST['title'], $_POST['event_date'], $_POST['location'], $_POST['description'], $image);
    echo json_encode(['success' => $stmt->execute()]);
    exit;
}

if ($action === 'delete_event' && $method === 'POST') {
    $stmt = $db->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param('i', $_POST['id']);
    echo json_encode(['success' => $stmt->execute()]);
    exit;
}

if ($action === 'get_news') {
    $result = $db->query("SELECT * FROM news ORDER BY date_posted DESC");
    $data = [];
    while ($row = $result->fetch_assoc()) $data[] = $row;
    echo json_encode($data);
    exit;
}

if ($action === 'add_news' && $method === 'POST') {
    $stmt = $db->prepare("INSERT INTO news (title, content, date_posted) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $_POST['title'], $_POST['content'], $_POST['date_posted']);
    echo json_encode(['success' => $stmt->execute()]);
    exit;
}

if ($action === 'delete_news' && $method === 'POST') {
    $stmt = $db->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param('i', $_POST['id']);
    echo json_encode(['success' => $stmt->execute()]);
    exit;
}

if ($action === 'get_gallery') {
    $result = $db->query("SELECT * FROM gallery ORDER BY id DESC");
    $data = [];
    while ($row = $result->fetch_assoc()) $data[] = $row;
    echo json_encode($data);
    exit;
}

if ($action === 'add_gallery' && $method === 'POST') {
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) $image = 'uploads/' . $filename;
    }
    $stmt = $db->prepare("INSERT INTO gallery (image_path, caption, date_posted) VALUES (?, ?, ?)");
    $date = date('Y-m-d');
    $stmt->bind_param('sss', $image, $_POST['caption'], $date);
    echo json_encode(['success' => $stmt->execute()]);
    exit;
}

if ($action === 'delete_gallery' && $method === 'POST') {
    $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param('i', $_POST['id']);
    echo json_encode(['success' => $stmt->execute()]);
    exit;
}

echo json_encode(['error' => 'Invalid action']);
?>