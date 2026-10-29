<?php
require __DIR__ . '/db.php';

$error = "";
$notice = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $pdo->prepare(
    "SELECT COUNT(*) FROM login_attempts
     WHERE ip = ? AND success = 0
     AND time_of_attempt > NOW() - INTERVAL 10 MINUTE"
    );
    $stmt->execute([$ip]);
    $failures = $stmt->fetchColumn();

    if($failures>=5)
        {
            $error = "Too many attempts. Try again later";
        }
    else{
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
          $success = 1;
         } 
        else {
          $success = 0;
         }

   
        $stmt=$pdo->prepare("INSERT INTO login_attempts (username,ip,success) VALUES (?,?,?)");
         $stmt->execute([$username,$ip,$success]);

   

        if ($success) {
          $notice = "Login successful";
         }
        else {
          $error = "Username or password is invalid";
         }

        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Log in</title>
<link rel="stylesheet" href="style_login.css">
</head>
<body>

<form method="post">
<h1>Welcome back</h1>
<p class="sub">Log in to continue.</p>

<label>Username
<input type="text" name="username" placeholder="Your username" required>

</label>

<label>Password
<input type="password" name="password" placeholder="Your password" minlength="8" required>
</label>

<p class="error"><?= htmlspecialchars($error) ?></p>
<p class="notice"><?= htmlspecialchars($notice) ?></p>

<button type="submit">Log in</button>

<p class="switch">Don't have an account? <a href="register.php">Register</a></p>

</form>

</body>
</html>