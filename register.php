<?php
include_once "config.php";
global $serverName, $dbName, $userName, $password;

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
    if($_POST['name']!='' && $_POST['email']!='' && $_POST['username']!='' && $_POST['password']!='') {


        $conn = mysqli_connect("$serverName", "$userName", "$password", "$dbName");
        $query = mysqli_query($conn, "SELECT user_name from users");
        $founded = false;
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            if ($row["user_name"] == $_POST['username']) {
                $founded = true;
            }
        }
        if ($founded) {
            echo "Uzivatel s danym username existuje";
        } else {
            session_start();
            $_SESSION['name'] = $_POST['name'];
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['userName'] = $_POST['username'];
            $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            header("Location: generateQrCode.php");
        }
    }
}
?>


<!doctype html>
<html lang=sk>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
    <title>Register</title>
</head>
<body>

<div class="centered-content">
    <div class="blue-box">
        <header>
            <h1>Registrácia</h1>
        </header>
        <form action="register.php" method="post">
            <div>
                <label for="name">Name</label>
                <input id="name" class="form-control" name="name" type="text">
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" class="form-control" name="email" type="email">
            </div>
            <div>
                <label for="username">User Name</label>
                <input id="username"  class="form-control" name="username" type="text">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <input type="submit" class="btn btn-primary" value="Overiť">
        </form>
    </div>
</div>
</body>
</html>


