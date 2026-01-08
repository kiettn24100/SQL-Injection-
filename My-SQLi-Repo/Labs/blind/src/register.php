<?php
    ini_set('display_errors',0);
    ini_set('display_startup_errors',0);


    include 'db.php';
    $message = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["username"]) && isset( $_POST["password"]) && isset($_POST["repassword"])){
            $username = $_POST["username"];
            $password = $_POST["password"]; 

            $sql = "INSERT INTO users(username,password) VALUES ('$username', '$password')";

            try {
                $result = $conn->query($sql);
            }catch (Throwable $error) {
            
            }


            echo "Đã gửi dữ liệu , hãy đợi xác nhận!";
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
    <h2>Đăng kí</h2>
    <form method="POST" action="register.php">
        <label for="username">username:</label>
        <input type="text" name="username">
        <label for="password">Password:</label>
        <input type="password" name="password">
        <label for="repassword">Repeat password:</label>
        <input type="password" name="repassword">
        <button type="submit" name="Register" value="reigster">Register</button>
    </form>
    <h3><?php echo $message; ?></h3>
</body>
</html>