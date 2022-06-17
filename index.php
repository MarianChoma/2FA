<?php
// include your composer dependencies
require_once 'vendor/autoload.php';

$client = new Google\Client();
$client->setAuthConfig('client_secret_843496591745-rlql092f7o5e7ch18eidnep6anqcj74k.apps.googleusercontent.com.json');
$redirect_uri = 'https://site82.webte.fei.stuba.sk/Zadanie3/redirect.php';
$client->addScope('email');
$client->addScope('profile');
$client->setRedirectUri($redirect_uri);

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">

    <title>Zadanie3</title>
</head>
<body>
<div class="centered-content">
    <div class="blue-box">
        <header>
            <h1>Prihlásenie</h1>
        </header>
        <form action="login.php" method="post">
            <div>
                <label for="username">User Name</label>
                <input id="username" class="form-control" name="username" type="text">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <input type="submit" class="btn btn-primary" value="Prihlásiť sa">
        </form>

        <?php
        echo "<span>Prihlásiť sa pomocou";
        echo "<a href='" . $client->createAuthUrl() . "'>Google účtu</a>";
        echo "</span>";
        ?>
        <div>
            <span> Nemas ucet? <a href="register.php">Zaregistruj sa</a></span>
        </div>
    </div>


</div>
</body>
</html>






