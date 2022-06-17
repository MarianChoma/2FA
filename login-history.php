<?php
include_once "config.php";
global $serverName, $dbName, $userName, $password;

$myPdo = new PDO("mysql:host=$serverName;dbname=$dbName", "$userName", "$password");
$myPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();
if (!isset($_SESSION['userName'])) {
    header("Location: index.php");
} else {
    $stmt = $myPdo->prepare("SELECT users.user_name, users.id, 
accounts.id, 
logins.account_id, logins.created_at 
from users 
JOIN accounts on 
users.id=accounts.user_id
JOIN logins on 
accounts.id=logins.account_id 
where users.user_name LIKE ?");

    $stmt->execute([$_SESSION['userName']]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();


    $stmt2= $myPdo->prepare("SELECT COUNT(logins.account_id), accounts.type
FROM logins 
    JOIN accounts ON 
        logins.account_id=accounts.id 
WHERE accounts.type='classic';");
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $classic = $stmt2->fetchAll();

    $stmt3= $myPdo->prepare("SELECT COUNT(logins.account_id), accounts.type
FROM logins 
    JOIN accounts ON 
        logins.account_id=accounts.id 
WHERE accounts.type='google';");
    $stmt3->execute();
    $stmt3->setFetchMode(PDO::FETCH_ASSOC);
    $google = $stmt3->fetchAll();
}

?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="login-history.css">
    <title>Login History</title>
</head>
<body>
<header>
    <h1>História prihlásení</h1>
</header>
<div class="container">
    <?php
    if (sizeof($result) > 0){
    ?>
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Čas prihlásenia</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($result as $item) {

            echo '<tr>';
            echo "<td>" . $item["created_at"] . "</td>";
            echo '</tr>';

        }
        ?>
        </tbody>
        <?php
        }
        ?>
    </table>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Počet klasických prihlásení</th>
            <th>Počet prihlásení pomocou Google účtu</th>
        </tr>
        </thead>
        <tbody>
        <?php
        echo '<tr>';
        echo "<td>" . $classic[0]["COUNT(logins.account_id)"] . '</td>';
        echo "<td>" . $google[0]["COUNT(logins.account_id)"] . '</td>';
        echo '</tr>';
        ?>
        </tbody>
    </table>

</div>


</body>
</html>
