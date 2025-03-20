<?php
session_start();
if (!isset($_SESSION["benutzer_alias"])) {
    header("Location: login.php");
    exit();
}
    $link = mysqli_connect("localhost:3308", "root", "", "biliardshop")
    or exit("Keine Verbindung zu MySQL");
    $sql = "SELECT * from essen_trinken";
    $result = mysqli_query($link, $sql);
    $artikel = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $artikel[] = $row;
    }

    $filiale = $_GET['filiale'];

$sql = "SELECT startzeit,endzeit,tisch.tisch_id,preis_pro_stunde from buchung,tisch,filiale where buchung.tisch_id=tisch.tisch_id and tisch.filiale_id=filiale.filiale_id and filiale.filiale_id=$filiale";
    $result = mysqli_query($link, $sql);
    $buchungen = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $buchungen[] = $row;
    }






?>
<html>
<head>
    <title>Buchung</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="site">
    <ul>
        <li><a href="login.php">Login</a></li>
    </ul>
    <div id="booking-form">
        <h1>Buchung</h1>
        <form action="submit_booking.php" method="post">
            <input type="time" name="start-time" id="start-time" list="time-list-start" required="required" />

            <datalist id="time-list-start" >
                <option id="10start" name="10start"  value="10:00" datatype="time">
                <option id="11start" name="11start" value="11:00" datatype="time">
                <option id="12start" name="12start" value="12:00" datatype="time">
                <option id="13start" name="13start" value="13:00" datatype="time">
                <option id="14start" name="14start" value="14:00" datatype="time">
                <option id="15start" name="15start" value="15:00" datatype="time">
                <option id="16start" name="16start" value="16:00" datatype="time">
                <option id="17start" name="17start" value="17:00" datatype="time">
                <option id="18start" name="18start" value="18:00" datatype="time">
                <option id="19start" name="19start" value="19:00" datatype="time">
                <option id="20start" name="20start" value="20:00" datatype="time">
            </datalist>

            <input type="time" name="end-time" id="end-time" list="time-list-end" required="required" />

            <datalist id="time-list-end" >
                <option id="11end" name="11end" value="11:00" datatype="time">
                <option id="12end" name="12end" value="12:00" datatype="time">
                <option id="13end" name="13end" value="13:00" datatype="time">
                <option id="14end" name="14end" value="14:00" datatype="time">
                <option id="15end" name="15end" value="15:00" datatype="time">
                <option id="16end" name="16end" value="16:00" datatype="time">
                <option id="17end" name="17end" value="17:00" datatype="time">
                <option id="18end" name="18end" value="18:00" datatype="time">
                <option id="19end" name="19end" value="19:00" datatype="time">
                <option id="20end" name="20end" value="20:00" datatype="time">
                <option id="21end" name="21end" value="21:00" datatype="time">
            </datalist>





            <h2>Produkte im Warenkorb</h2>
            <div id="products-ordered">
                <?php
                if (isset($_SESSION['quantities'])) {
                    $quantities = $_SESSION['quantities'];
                    foreach ($quantities as $key => $quantity) {
                        if ($quantity == 0) {
                            continue;
                        }
                        echo "<p>Artikel: " . $artikel[$key]['bezeichnung'] . " | Menge: " . $quantity . "</p>";
                    }

                }

                ?>
            </div>

            <h2></h2>

            <button type="submit" class="btn btn-primary btn-block btn-large">Order</button>
        </form>
    </div>
</div>
</body>
</html>