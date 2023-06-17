<?php
// Seite für die Nachrichtenverfassung
// Hier werden alle Nachrichten angezeigt

session_start();

$servername = "localhost";
$db_name = "Users";
$username = "root"; // Standardbenutzername für lokale Installationen
$password = ""; // Standardpasswort für lokale Installationen

$db = new PDO("mysql:host=$servername;dbname=$db_name;charset=utf8mb4", $username, $password);

// Überprüfung, ob ein Benutzer angemeldet ist
if (!isset($_SESSION['user'])) {
    // Umleitung zur Login-Seite, wenn kein Benutzer angemeldet ist
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Die ID aus der URL wird in einer Variable gespeichert
    $getId = $_GET['id'];

    // Überprüfen in der Datenbank, ob die ID einem vorhandenen Benutzer entspricht
    $recupUser = $db->prepare("SELECT * FROM Users WHERE id = ?");
    $recupUser->execute(array($getId));

    if ($recupUser->rowCount() > 0) {
        // Wenn der Benutzer existiert

        if (isset($_POST['envoyer'])) {
            // Wenn der Benutzer auf "Senden" klickt

            // Die eingegebene Nachricht wird in einer Variable gespeichert
            // Die nl2br()-Funktion wird verwendet, um Zeilenumbrüche zuzulassen
            $message = nl2br($_POST['message']);

            // Überprüfen, ob die Nachricht leer ist
            if (empty($message)) {
                echo "Fehler: Bitte geben Sie eine Nachricht ein.";
            } else {
                // SQL-Abfrage zum Einfügen der Nachricht in die Datenbank
                $insertMessage = $db->prepare("INSERT INTO Nachricht(Nachricht, idEmpfaenger, idSender) VALUES (?, ?, ?)");
                $insertMessage->execute(array($message, $getId, $_SESSION['id']));

                // Weiterleitung zur Chat-Seite, um die Nachricht anzuzeigen
                header("Location: message.php?id=$getId");
                exit;
            }
        }

        if (isset($_POST['loeschen'])) {
            // Wenn der Benutzer auf "Löschen" klickt

            // Die ID der zu löschenden Nachricht wird in einer Variable gespeichert
            $messageId = $_POST['message_id'];

            // SQL-Abfrage zum Löschen der Nachricht aus der Datenbank
            $deleteMessage = $db->prepare("DELETE FROM Nachricht WHERE id = ?");
            $deleteMessage->execute(array($messageId));
        }
    } else {
        echo "Benutzer nicht gefunden";
    }
} else {
    echo "Keine ID gefunden";
}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Nachrichten</title>
</head>
<body>
    <h1>Nachrichten</h1>
    <form method="POST" action="">
        <!-- Feld, in dem der Benutzer seine Nachricht verfasst (mit dem Namen "message") -->
        <textarea name="message"></textarea><br><br>
        <input type="submit" name="envoyer" value="Senden">
    </form>
    
   <section id="messages">
    <?php
    $recupMessage = $db->prepare('SELECT * FROM Nachricht WHERE (idSender = ? AND idEmpfaenger = ?) OR (idSender = ? AND idEmpfaenger = ?)');
    $recupMessage->execute(array($_SESSION['id'], $getId, $getId, $_SESSION['id']));

    while ($message = $recupMessage->fetch()) {
        if ($message['idEmpfaenger'] == $_SESSION['id']) {
            echo '<div class="messageContainer">';
            echo '<div class="messageRContainer">';
            echo '<p class="messageR">' . $message['Nachricht'] . '</p>';
            echo '<form method="POST" action=""><input type="hidden" name="message_id" value="' . $message['id'] . '"><input type="submit" name="loeschen" value="Löschen"></form>';
            echo '</div>';
            echo '</div>';
        } elseif ($message['idEmpfaenger'] == $getId) {
            echo '<div class="messageContainer">';
            echo '<div class="messageEContainer">';
            echo '<p class="messageE">' . $message['Nachricht'] . '</p>';
            echo '<form method="POST" action=""><input type="hidden" name="message_id" value="' . $message['id'] . '"><input type="submit" name="loeschen" value="Löschen"></form>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</section>
</body>
</html>

