
<!DOCTYPE html>
<html>
<body>

<form method ="post">
User Name<input type="text" name="username" required><br>
Password<input type="password" name="password" required><br>
<button type= "submit"> Login</button>

</form>

</body>
</html>
<?php
require __DIR__ . '/db.php';

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
            echo "Too many attempts.Try again later";
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
          echo "Login successful";
         }
        else {
          echo "Username or password is invalid";
         }

        }
}
?>

