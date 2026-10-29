<?php
require __DIR__ . '/db.php';

$error = "";
$notice = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username= trim($_POST['username'] ?? '');
    $email= trim($_POST['email'] ?? '');
    $password= $_POST['password'] ?? '';

    $hpassword=password_hash($password,PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();


    if ($user) {
       $error = "Email already in use";
     } 
  else {
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->execute([$username, $email, $hpassword]);

      $notice = "Registration successful";
    }
    
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register</title>
<link rel="stylesheet" href="style_register.css">
</head>
<body>

<form method="post">
<h1>Create an account</h1>
<p class="sub">Fill in your details.</p>

<label>User Name
<input type="text" name="username" placeholder="Your username" required>
</label>

<label>E-mail
<input type="email" name="email" placeholder="you@example.com" required>
</label>

<label>Password
<input type="password" name="password" placeholder="Your password" minlength="8" required>
</label>

<p class="error"><?= htmlspecialchars($error) ?></p>
<p class="notice"><?= htmlspecialchars($notice) ?></p>

<button type="submit">Register</button>

<p class="switch">Already have an account? <a href="login.php">Log in</a></p>

</form>

</body>
</html>