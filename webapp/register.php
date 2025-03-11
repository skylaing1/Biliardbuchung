
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="site">
    <ul>
        <li><a href="login.php">Login</a></li>
    </ul>
    <div class="register">
        <h1>Register</h1>
        <form action="submit_register.php" method="post">
            <input type="text" name="name" placeholder="Name" required="required" />
            <input type="text" name="lastname" placeholder="Last Name" required="required" />
            <input type="text" name="username" placeholder="Username" required="required" />
            <input type="password" name="password" placeholder="Password" required="required" />
            <input type="email" name="email" placeholder="Email" required="required" />
            <input type="tel" name="phone" placeholder="Phone Number" required="required" />
            <button type="submit" class="btn btn-primary btn-block btn-large">Register</button>
        </form>
    </div>
</div>
</body>
</html>