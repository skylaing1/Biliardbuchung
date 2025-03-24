<?php
session_start();

if (isset($_SESSION["benutzer_alias"])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
$benutzer_vorname = $_POST["name"];
$benutzer_name = $_POST["lastname"];
$benutzer_alias = $_POST["username"];
$benutzer_passwort = $_POST["password"];
$benutzer_email = $_POST["email"];
$benutzer_telefon = $_POST["phone"];
$link = mysqli_connect("localhost:3306", "root", "", "biliardshop")
or exit("Keine Verbindung zu MySQL");
$sql = "INSERT INTO benutzer (benutzer_vorname, benutzer_name, benutzer_alias, benutzer_password, email, telefon)
VALUES ('$benutzer_vorname', '$benutzer_name', '$benutzer_alias', '$benutzer_passwort', '$benutzer_email', '$benutzer_telefon')";
if (mysqli_query($link, $sql)) {
echo "Benutzer erfolgreich registriert";
}
else {
    echo "Fehler beim Registrieren des Benutzers: " . mysqli_error($link);
}
mysqli_close($link);
}
?>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/credentials.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="site">
    <ul>
        <li><a href="login.php">Login</a></li>
    </ul>
    <div class="register">
        <h1>Register</h1>
        <form action="register.php" method="post">
            <input type="text" name="name" placeholder="Vorname" required="required" />
            <input type="text" name="lastname" placeholder="Nachname" required="required" />
            <input type="text" name="username" placeholder="Benutzername" required="required" />
            <input type="password" name="password" placeholder="Password" required="required" />
            <input type="email" name="email" placeholder="Email" required="required" />
            <input type="tel" name="phone" placeholder="Telefonnummer" required="required" />
            <button type="submit" class="btn btn-primary btn-block btn-large">Register</button>
        </form>
    </div>
</div>
</body>
</html>