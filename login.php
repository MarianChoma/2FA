<?php

include_once "config.php";
global $serverName, $dbName, $userName, $password;
$myPdo = new PDO("mysql:host=$serverName;dbname=$dbName", "$userName", "$password");
if(isset($_POST['username']) && isset($_POST['password'])){
    try{
        $myPdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stmt = $myPdo->prepare("SELECT name,user_name,password,user_id,accounts.id FROM users join accounts ON users.id = accounts.user_id WHERE user_name = ? AND `type`='classic'");
        $stmt->execute([$_POST['username']]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();
        if(password_verify($_POST['password'],$user['password'])){
            $stmt = $myPdo->prepare("INSERT INTO logins (account_id) VALUES (".$user["id"].")");
            $stmt->execute();
            session_start();
            $_SESSION['userName'] = $user['user_name'];
            header( "Location: dashboard.php" );
        }
    }catch(PDOException $e){
        echo "Error: ". $e->getMessage();
    }
}
?>