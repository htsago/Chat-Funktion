<?php
session_start();

$servername = "localhost";
$db_name = "Users";
$username = "root";
$password = "";

$db = new mysqli($servername, $username, $password, $db_name);

if ($db->connect_error) {
    die("Verbindungsfehler: " . $db->connect_error);
}

if (isset($_POST['absenden'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_wiederholen = $_POST['password_wiederholen'];

    $search_user = $db->prepare("SELECT * FROM Users WHERE USERNAME = ?");
    $search_user->bind_param('s', $username);
    $search_user->execute();
    $search_result = $search_user->get_result();

    if ($search_result->num_rows == 0) {
        if ($password == $password_wiederholen) {
            $insert = $db->prepare("INSERT INTO Users (USERNAME, PASSWORD) VALUES (?, ?)");
            $insert->bind_param('ss', $username, $password);
            $insert->execute();

            if ($insert->affected_rows > 0) {
                echo 'Dein Account wurde erfolgreich erstellt!';
            }
        } else {
            echo 'Die Passwörter stimmen nicht überein.';
        }
    } else {
        echo 'Der Benutzername ist leider schon vergeben.';
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Registrierungsformular</title>
</head>
<body>
    <div class="header">
        <h2>Registrierungsformular</h2>
    </div>
    <form method="post" autocomplete="on">
        <div class="input">
            <label>Username</label>
            <input type="text" name="username" required="" id="name">
        </div>
        <div class="input">
            <label>Passwort</label>
            <input type="password" name="password" required="" id="password">
        </div>
        <div class="input">
            <label>Passwort bestätigen</label>
            <input type="password" name="password_wiederholen" required="" id="password_wiederholen">
        </div>
        <input type="submit" class="btn" name="absenden" value="Registrieren">
        <p>Haben Sie bereits einen Account? <a href="login.php" style="text-decoration: none;">Login</a></p>
    </form>
</body>
</html>

