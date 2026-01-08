<?php
    session_start();
    include 'db.php';
    $message = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

        $result = $conn->query($sql);

        if ($result-> num_rows >0) {
            $row = $result-> fetch_assoc();
            $_SESSION['user'] = $row['username'];
            $message = "Đăng nhập thành công . Hello". $row['username'];
        }else{
            $message = "Sai tên đăng nhập hoặc mật khẩu";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form method = "POST" action="login.php">
        <label for="username">Username</label></br>
        <input type="text" name="username"></br>
        <label for="password">Password</label></br>
        <input type="password" name="password"></br>
        <button type="submit" name="login" value="login">Login</button>
    </form>
</br>
<?php echo $message; ?>
</body>
</html>