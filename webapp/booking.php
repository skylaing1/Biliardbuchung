<?php
session_start();
if (!isset($_SESSION["benutzer_alias"])) {
    header("Location: login.php");
    exit();
}

$link = mysqli_connect("localhost:3306", "root", "", "biliardshop")
or exit("Keine Verbindung zu MySQL");

$filiale = $_GET['filiale'] ?? 1; // Falls keine Filiale gewählt wurde, Standardwert setzen
$tisch_frei = false;
$meldung = "";
$preis = 0;
$tisch_id = null;

// Wenn das Formular abgesendet wurde, prüfen wir die Tischverfügbarkeit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start = $_POST['start-time'];
    $end = $_POST['end-time'];

    if ($start && $end) {
        // Prüfe, ob ein Tisch frei ist vergiss datum nicht
        $sql = "SELECT tisch.tisch_id, tisch.preis_pro_stunde FROM tisch
        WHERE tisch.filiale_id = ?
        AND tisch.tisch_id NOT IN (
            SELECT buchung.tisch_id FROM buchung 
            WHERE (
                (buchung.startzeit < ? AND buchung.endzeit > ?) OR
                (buchung.startzeit >= ? AND buchung.startzeit < ?)
            ) AND buchung.datum = CURDATE()
        ) LIMIT 1";

        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "issss", $filiale, $end, $start, $start, $end);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $tisch_frei = true;
            $tisch_id = $row['tisch_id'];
            $preis_pro_stunde = $row['preis_pro_stunde'];

            // Zeitdifferenz berechnen
            $start_datetime = new DateTime($start);
            $end_datetime = new DateTime($end);
            $stunden = $start_datetime->diff($end_datetime)->h; // Nur volle Stunden

            $preis = $stunden * $preis_pro_stunde;
            $meldung = "<p style='color: green;'>Ein Tisch ist frei! Gesamtkosten: <b>$preis €</b></p>";
        } else {
            $meldung = "<p style='color: red;'>Kein Tisch verfügbar im gewählten Zeitraum!</p>";
        }

        mysqli_stmt_close($stmt);
    }
}

?>
<html>
<head>
    <title>Buchung</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/buchung.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="site">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
    <div id="booking-form">
        <h1>Buchung</h1>
        <form action="" method="post">
            <label for="start-time">Startzeit:</label>
            <input type="time" name="start-time" step="3600" id="start-time" required="required" />

            <label for="end-time">Endzeit:</label>
            <input type="time" name="end-time" step="3600" id="end-time" required="required" />

            <button type="submit" class="btn btn-primary btn-block btn-large">Verfügbarkeit prüfen</button>
        </form>

        <!-- Ergebnis der Überprüfung -->
        <div id="availability">
            <?php echo $meldung; ?>
        </div>

        <!-- Weiter zum Warenkorb-Button order.php -->
        <?php if ($tisch_frei): ?>
            <a href="order.php?filiale=<?php echo $filiale; ?>">
                <?php
                // Tisch in der Session speichern
                $_SESSION['tisch_id'] = $tisch_id;
                $_SESSION['tisch_preis'] = $preis;
                $_SESSION['startzeit'] = $_POST['start-time'];
                $_SESSION['endzeit'] = $_POST['end-time'];
                ?>
                <button class="btn btn-primary btn-block btn-large">Weiter zum Warenkorb</button>
            </a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
