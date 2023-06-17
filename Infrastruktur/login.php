<?php
session_start();

$servername = "localhost";
$db_name = "Users";
$username = "root";
$password = "";

$db = new PDO("mysql:host=$servername;dbname=$db_name;charset=utf8mb4", $username, $password);

if (isset($_POST['absenden'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $searchUserQuery = $db->prepare("SELECT * FROM Users WHERE USERNAME = :username AND PASSWORD = :password");
    $searchUserQuery->bindParam(':username', $username);
    $searchUserQuery->bindParam(':password', $password);
    $searchUserQuery->execute();

    $user = $searchUserQuery->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user'] = $user['USERNAME'];
        $_SESSION['id'] = $user['id'];
        header("Location: index.php?username=" . $_SESSION['user']);
        exit;
    } else {
        echo '<script>alert("Deine Angaben sind leider nicht korrekt.");</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Add your custom CSS styles here */
    </style>
    <title>Login</title>
</head>
<body>
    <div class="header">
        <h2>Login</h2>
    </div>
    <form method="post" action="">
        <div class="input">
            <label>Username</label>
            <input type="text" name="username" required="">
        </div>
        <div class="input">
            <label>Passwort</label>
            <input type="password" name="password" required="">
        </div>
        <div class="input">
            <button type="submit" name="absenden" class="btn">Login</button>
        </div>
        <p>Hast du noch keinen Account? <a href="registrieren.php" style="text-decoration: none;">Registrierung</a></p>
    </form>
</body>
</html>

