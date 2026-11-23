<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    die("❌ Nta mazina cyangwa password winjije. Subira inyuma.");
}

$db = getDB();

if (!$db) {
    die("❌ Database yanze gufunguka. Reba muri db.php niba amakuru ari yo.");
}

$stmt = $db->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && $password === $user['password']) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header('Location: ../admin/index.php');
        exit;
    } elseif ($user['role'] === 'editor') {
        header('Location: ../editor/index.php');
        exit;
    }
} else {
    die("❌ Username cyangwa Password si byo. (Reba niba password muri phpMyAdmin ari 'Admin@2026')");
}
?>