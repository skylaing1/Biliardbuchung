
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
            <label for="start-time">Select Start Time:</label>
            <input type="time" id="start-time" name="start-time" required>

            <label for="end-time">Select End Time:</label>
            <input type="time" id="end-time" name="end-time" required>

            <label for="personen">Personen:</label>
            <input type="number" id="personen" name="personen" min="1" required>

            <h2>Products Ordered</h2>
            <div id="products-ordered">
                <!-- Placeholder for products ordered -->
                <p>Product 1: 2 units</p>
                <p>Product 2: 1 unit</p>
            </div>

            <h2>Total Price: $30</h2>

            <button type="submit" class="btn btn-primary btn-block btn-large">Order</button>
        </form>
    </div>
</div>
</body>
</html>