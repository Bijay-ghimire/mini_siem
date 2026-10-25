<!DOCTYPE html>
<html>
<body>

<form method ="post">
User Name<input type="text" name="username" required><br>
E-mail<input type="email" name="email" required><br>
Password<input type="password" name="password" minlength="8" required><br>
<button type= "submit"> Register</button>

</form>

</body>
</html>
<?php
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name= trim($_POST['username']);
    $email= trim($_POST['email']);
    $password= trim($_POST['password']);
    $hpassword=password_hash($password,PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username,email,password) VALUES (?, ?,?)");
    $stmt->execute([$name, $email, $hpassword]);


}
?>


