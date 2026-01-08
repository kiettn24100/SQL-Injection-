<?php
    include 'db.php';
    $message = "";
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST["username"]) && isset( $_POST["password"]) && isset( $_POST["repassword"]) && isset( $_POST["email"])){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $repassword = $_POST["repassword"];
            $email = $_POST["email"];

        if($password != $repassword){
            $message ="Mật khẩu không trùng khớp";
        }else {
        $sql = "INSERT INTO users (username ,password,email ) VALUES ( '$username', '$password','$email' )";
        $result = $conn->query($sql);
        if($result === TRUE){
            $message = "Đăng kí thành công!. Hãy quay lại trang đăng nhập";
            header("Location: login.php");
        }else{
            $message = "Lỗi :".$conn->error;
        }
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="register.php">
        <label for="username">Username</label></br>
        <input type="text" name="username"></br>
        <label for="password">Password</label> </br>
        <input type="password" name="password"></br>
        <label for="repassword">Repeat password</label></br>
        <input type="password" name="repassword"></br>
        <label for="email">email</label></br>
        <input type="email" name="email"></br>
        <button type="submit" name="register" value="Register">Register</button></br>
    </form>
    <h3><?php echo $message; ?></h3>
</body>
</html>