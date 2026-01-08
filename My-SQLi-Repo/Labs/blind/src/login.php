<?php
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
  //  error_reporting(E_ALL);

    session_start();
    include 'db.php';
    $message = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") { 
        if (isset($_POST["username"]) && isset( $_POST["password"])) {
            $username = $_POST["username"];
            $password = $_POST["password"];

            $sql = "SELECT * FROM users WHERE username = '$username' AND password ='$password'";
            
            $result = NULL;
            try{
                $result = $conn->query($sql);
            } catch(Throwable $error) {
                
            }
            
            if ($result->num_rows > 0) {
                $message = 'Login thành công';
            } else {
                $message = 'Login thất bại';
            }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blind SQL Injection</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username">
        <label for="password">Password</label>
        <input type="password" name="password">
        <button type="submit" name="login" value="login">Login</button>
    </form>
    <h3><?php echo $message; ?></h3>
</body>
</html>