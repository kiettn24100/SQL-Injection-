<?php
    ini_set('display_errors',0);
    ini_set('display_startup_errors',0);

    session_start();
    include 'db.php';
    set_time_limit(1);
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        if (isset($_POST["username"]) && isset($_POST["password"])){
            $username = $_POST["username"];
            $password = $_POST["password"];

            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

            try {
                $result = $conn->query($sql);
            }catch(Throwable $errors){

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Out of band SQL Injection</title>
</head>
<body>
    <h2>Lab Out of band SQL Injection</h2>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username">
        <label for="password">Password:</label>
        <input type="password" name="password">
        <button type="submit" name="login" value="login">Login</button>
    </form>
    <h3><?php echo $message; ?></h3>
</body>
</html> 