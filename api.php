<?php
header('Content-Type: application/json');
require_once 'db.php';

$action = $_POST['action'] ?? '';

if ($action === 'fetch') {
    $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
    exit;
}

if ($action === 'add') {
    $name = trim($_POST['name'] ?? '');
    $age = intval($_POST['age'] ?? 0);

    if (!empty($name) && $age > 0) {
        $stmt = $conn->prepare("INSERT INTO users (name, age, status) VALUES (?, ?, 0)");
        $stmt->bind_param("si", $name, $age);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'بيانات غير صالحة']);
    }
    exit;
}

if ($action === 'toggle') {
    $id = intval($_POST['id'] ?? 0);
    $current_status = intval($_POST['current_status'] ?? 0);
    $new_status = ($current_status == 1) ? 0 : 1;

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_status, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'معرف غير صالح']);
    }
    exit;
}

echo json_encode(['error' => 'إجراء غير معروف']);
?>