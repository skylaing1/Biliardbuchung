<?php
session_start();
if (isset($_SESSION["benutzer_alias"])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $benutzer_alias = $_POST["username"];
    $benutzer_passwort = $_POST["password"];

    $link = mysqli_connect("localhost:3306", "root", "", "biliardshop")
    or exit("Keine Verbindung zu MySQL");

    $sql = "SELECT * FROM benutzer WHERE benutzer_alias = '$benutzer_alias' AND benutzer_password = '$benutzer_passwort'";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) == 1) {
        $_SESSION["benutzer_alias"] = $benutzer_alias;
        $_SESSION["benutzer_id"] = mysqli_fetch_assoc($result)["benutzer_id"];
        header("Location: index.php");
        exit();
    }
    else {
        echo "Login fehlgeschlagen";
    }
    mysqli_close($link);

}
?>
<html>
<head>
    <title>Billiard Buchung</title>
    <link rel="stylesheet" type="text/css" href="css/credentials.css">
</head>
<body>
    <div class="login">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required="required" />
            <input type="password" name="password" placeholder="Password" required="required" />
            <button type="submit" class="btn btn-primary btn-block btn-large">Let me in.</button>
        </form>
    </div>
</html>
