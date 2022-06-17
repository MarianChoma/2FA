<?php session_start();
if (!isset($_SESSION['userName'])) {
    header("Location: index.php");
}
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
    <title>Dashbourd</title>
</head>
<body>
<div class="centered-content">
    <div class="blue-box">
        <h1>Vitaj <?php echo $_SESSION['userName'] ?></h1>
        <br>
        <h4>Prihlásenie prebehlo úspešne</h4>
        <a href="logout.php"><button class="btn btn-primary">Odhlasiť sa</button></a>
        <a class="history" href="login-history.php"><button class="btn btn-primary">História prihlásení</button></a>
    </div>
</div>
</body>
</html>


