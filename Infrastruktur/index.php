<?php
session_start();

$servername = "localhost";
$db_name = "Users";
$username = "root";
$password = "";

$db = new PDO("mysql:host=$servername;dbname=$db_name;charset=utf8mb4", $username, $password);

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$usersQuery = $db->query('SELECT * FROM Users');
$users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Add your custom CSS styles here */
    </style>
    <title>Startseite</title>
</head>

<body>
    <nav>
        <ul>
            <li><a href="logout.php"><b>Abmelden</b></a></li>
        </ul>
    </nav>
    <div class="container">
        <h1>Liste aller Benutzer</h1>
        <?php foreach ($users as $user) : ?>
            <a href="message.php?id=<?= $user['id'] ?>">
                <p><?= $user['USERNAME'] ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</body>

</html>

