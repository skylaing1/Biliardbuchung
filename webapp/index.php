<?php
session_start();

if (!isset($_SESSION["benutzer_alias"])) {
    header("Location: login.php");
    exit();
}

$host = "localhost:3306";
$user = "root";
$pw = "";
$db = "biliardshop";
$link = mysqli_connect($host, $user, $pw, $db)
or exit ("Keine Verbindung zu MySQL");
$sql = "SELECT * FROM filiale";
$result = mysqli_query($link, $sql);
$filialen = [];
while ($row = mysqli_fetch_assoc($result)) {
    $filialen[] = $row;
}


$sql = "SELECT * FROM essen_trinken";
$result = mysqli_query($link, $sql);
$artikel = [];
while ($row = mysqli_fetch_assoc($result)) {
    $artikel[] = $row;
}


mysqli_close($link);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //speicher die artikel in der session, speichere nur artikel die eine menge größer 0 haben


    $_SESSION['quantities'] = [];
    foreach ($_POST['quantity'] as $key => $value) {
        if ($value > 0) {
            $_SESSION['quantities'][$key] = $value;
        }
    }







    //add zu url filiale bookin.php?filiale=1
    header("Location: booking.php?filiale=" . $_POST['filaleSelect']);
    exit();




}
?>

<html>
<head>
    <title>Billiard Buchung</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="site">
    <ul>
        <li><a href="logout.php">Logout</a></li>
        <form method="post">
        <li>
            <select id="filale-select" name="filaleSelect">
                <?php foreach ($filialen as $filiale): ?>
                    <option value="<?php echo $filiale['filiale_id']; ?>">
                        <?php echo $filiale['bezeichnung']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </li>

    </ul>
    <div>
        <a>
            <button id="buttonbooking" type="submit" class="btn btn-primary btn-block btn-large">Buchung</button>
        </a>
    </div>

    <div id="product-list">
        <?php foreach ($artikel as $item): ?>
            <div class="product-card">
                <img src="images/<?php echo strtolower(str_replace(' ', '_', $item['bezeichnung'])); ?>.jpeg" alt="<?php echo $item['bezeichnung']; ?>">
                <h2><?php echo $item['bezeichnung']; ?></h2>
                <p>Preis: €<?php echo number_format($item['preis'], 2); ?></p>
                <div class="quantity-controls">
                    <button type="button" class="decrease" onclick="this.nextElementSibling.stepDown()">-</button>
                    <input name="quantity[<?php echo $item['essen_trinken_id']; ?>]" type="number" value="0" min="0">
                    <button type="button" class="increase" onclick="this.previousElementSibling.stepUp()">+</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </form>
</div>
</body>
</html>

