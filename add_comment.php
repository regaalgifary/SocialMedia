<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "socialize_db");
if ($mysqli->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB error']);
    exit();
}

$post_id = (int) $_POST['post_id'];
$comment = trim($_POST['comment']);
$user_id = $_SESSION['user_id'];

if ($comment === '') {
    echo json_encode(['status' => 'error', 'message' => 'Empty comment']);
    exit();
}

$stmt = $mysqli->prepare("INSERT INTO comments (id_post, id_user, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $post_id, $user_id, $comment);
$stmt->execute();
$stmt->close();

echo json_encode(['status' => 'success']);
?>
