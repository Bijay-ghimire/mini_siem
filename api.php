<?php
require __DIR__ . '/db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("SELECT * FROM login_attempts");
$attempts = $stmt->fetchAll();

echo json_encode($attempts);
?>