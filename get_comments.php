<?php
session_start();
if (!isset($_GET['post_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Post ID not found']);
  exit;
}

$mysqli = new mysqli("localhost", "root", "", "socialize_db");
$post_id = (int)$_GET['post_id'];

$stmt = $mysqli->prepare("SELECT c.comment, c.created_at, u.username, u.profile_pic 
                          FROM comments c 
                          JOIN users u ON c.id_user = u.id_user 
                          WHERE c.id_post = ? ORDER BY c.created_at ASC");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
  $row['created_at'] = date("M j, Y H:i", strtotime($row['created_at']));
  $comments[] = $row;
}

echo json_encode(['status' => 'success', 'comments' => $comments]);
?>
