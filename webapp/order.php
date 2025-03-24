<?php
session_start();

if (!isset($_SESSION["benutzer_alias"])) {
    header("Location: login.php");
    exit();
}

$link = mysqli_connect("localhost:3306", "root", "", "biliardshop")
or exit("Keine Verbindung zu MySQL");

$tisch_id = $_SESSION["tisch_id"];
$preis = $_SESSION["tisch_preis"];
$filiale_id = $_GET['filiale'] ?? 1; // Default to 1 if not provided


$sql = "SELECT * FROM filiale WHERE filiale_id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $filiale_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$filiale = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);


$quantities = $_SESSION['quantities'] ?? [];
$products = [];
$total_product_cost = 0;

if (!empty($quantities)) {
    $ids = implode(',', array_keys($quantities));
    $sql = "SELECT * FROM essen_trinken WHERE essen_trinken_id IN ($ids)";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $quantities[$row['essen_trinken_id']];
        $row['total_price'] = $row['quantity'] * $row['preis'];
        $total_product_cost += $row['total_price'];
        $products[] = $row;
    }
}

$total_cost = $preis + $total_product_cost;

mysqli_close($link);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Speichere die Bestellung in der Datenbank
    $link = mysqli_connect("localhost:3306", "root", "", "biliardshop")
    or exit("Keine Verbindung zu MySQL");

    $sql = "INSERT INTO buchung (tisch_id, datum, startzeit, endzeit, benutzer_id, tisch_preis)
            VALUES (?, CURDATE(), ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    $benutzer_id = $_SESSION['benutzer_id'];
    $start = $_SESSION['startzeit'];
    $end = $_SESSION['endzeit'];
    mysqli_stmt_bind_param($stmt, "isssi", $tisch_id, $start, $end, $benutzer_id, $preis);

    //Getränke in Buchungsdetails speichern buchung_id,essen_trinken_id,anzahl,einzelpreis

    if (mysqli_stmt_execute($stmt)) {
        $buchung_id = mysqli_insert_id($link);
        $sql = "INSERT INTO buchungdetails (buchung_id, essen_trinken_id, anzahl, einzelpreis)
            VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        foreach ($products as $product) {
            mysqli_stmt_bind_param($stmt, "iiid", $buchung_id, $product['essen_trinken_id'], $product['quantity'], $product['preis']);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);

        // Clear the session data
        unset($_SESSION['tisch_id']);
        unset($_SESSION['tisch_preis']);
        unset($_SESSION['startzeit']);
        unset($_SESSION['endzeit']);
        unset($_SESSION['quantities']);

        header("Location: index.php");
        exit();
    } else {
        echo "Fehler beim Speichern der Bestellung: " . mysqli_error($link);
    }


    mysqli_close($link);
}






?>

<html>
<head>
    <title>Bestell Zusammenfassung</title>
    <link rel="stylesheet" type="text/css" href="css/buchung.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="site">
    <h1>Zusammenfassung der Bestellung</h1>
    <h2>Filialen Details</h2>
    <p>Filiale: <?php echo $filiale['bezeichnung']; ?></p>
    <p>Ort: <?php echo $filiale['ort']; ?></p>

    <h2>Tisch Buchung</h2>
    <p>Tisch Nummer: <?php echo $tisch_id; ?></p>
    <p>Kosten: €<?php echo number_format($preis, 2); ?></p>

    <h2>Produkte</h2>
    <ol>
        <?php foreach ($products as $product): ?>
            <p>
                <?php echo $product['bezeichnung']; ?> - Anzahl: <?php echo $product['quantity']; ?> - Summe: €<?php echo number_format($product['total_price'], 2); ?>
            <p>
            <br>
        <?php endforeach; ?>
    </ol>

    <h2>Gesamt Kosten</h2>
    <p>€<?php echo number_format($total_cost, 2); ?></p>
<form method="post">

        <button type="submit" class="btn btn-primary btn-block btn-large">Bestellung Abschließen</button>

</form>
</div>
</body>
</html>
