<?php

use Google\Authenticator\GoogleAuthenticator;

include_once 'vendor/google-authenticator/FixedBitNotation.php';
include_once 'vendor/google-authenticator/GoogleAuthenticatorInterface.php';
include_once 'vendor/google-authenticator/GoogleAuthenticator.php';
include_once 'vendor/google-authenticator/GoogleQrUrl.php';
include_once "config.php";
global $serverName, $dbName, $userName, $password;


$g = new GoogleAuthenticator();
session_start();
$secret = $_SESSION['secret'];



$myPdo = new PDO("mysql:host=$serverName;dbname=$dbName", "$userName", "$password");
$myPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['google_code'])) {
    if ($g->checkCode($secret, $_POST['google_code'])) {
        $myPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $myPdo->prepare("INSERT INTO users (name,email,user_name) VALUES (?,?,?)");
        $stmt->execute([$_SESSION['name'], $_SESSION['email'], $_SESSION['userName']]);
        $user_id = $myPdo->lastInsertId();

        $stmt = $myPdo->prepare("INSERT INTO accounts (user_id,type,password) VALUES (" . $myPdo->lastInsertId() . ",'classic',?)");
        $passwordHash = $_SESSION['password'];//password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->execute([$passwordHash]);

        $stmt = $myPdo->prepare("INSERT INTO logins (account_id) VALUES (" . $myPdo->lastInsertId() . ")");
        $stmt->execute();
        header("Location: dashboard.php");
    } else {
        echo 'Heslo je nesprávne alebo už vypršala jeho platnosť!';
    }


}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
    <title>Document</title>
</head>
<body>
<div class="centered-content">
    <div class="blue-box">
        <form action="check.php" method="post">
            <div class="qrCode">
                <?php
                echo '<img src="' . $g->getURL('xchoma', 'localhost', $secret) . '" />';
                ?>
            </div>
            <div>
                <p>Stiahnite si prosím aplikáciu <b>Google Authenticator</b><br> do vášho smartphonu a oskenujte pomocou nej
                qr kód. <br>Následne zadajte 6 miesty kód a kliknite na tlačidlo registrovať.</p>
            </div>
            <div>
                <label for="google_code">Google kód</label>
                <input type="text" class="form-control"  name="google_code" id="google_code">
            </div>
            <input type="submit" class="btn btn-primary"  value="Registrovať">
        </form>
    </div>
</div>

</body>
</html>



