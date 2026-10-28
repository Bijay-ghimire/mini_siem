<?php
require __DIR__ . '/db.php';

header('Content-Type: application/json');
$sent = $_SERVER['HTTP_X_API_KEY'] ?? '';

if (!hash_equals($config['API_KEY'], $sent)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
else{
    $stmt = $pdo->query("SELECT * FROM login_attempts");
    $attempts = $stmt->fetchAll();

    echo json_encode($attempts);

}


?>