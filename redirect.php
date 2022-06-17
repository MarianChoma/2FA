<?php
require_once 'vendor/autoload.php';
include_once "config.php";
global $serverName, $dbName, $userName, $password;


$client = new Google\Client();
try {
    $client->setAuthConfig('client_secret_843496591745-rlql092f7o5e7ch18eidnep6anqcj74k.apps.googleusercontent.com.json');
} catch (\Google\Exception $e) {
}

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    $client->setAccessToken($token['access_token']);
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    $id = $google_account_info->getId();

    try {
        $myPdo = new PDO("mysql:host=$serverName;dbname=$dbName", "$userName", "$password");
        $myPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conn = mysqli_connect("$serverName","$userName","$password","$dbName");
        $query = mysqli_query( $conn,"SELECT user_name from users");
        $founded= false;

        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            if($row["user_name"]=="User" . $id){
                $founded=true;
            }
        }
        if(!$founded) {

            $stmt = $myPdo->prepare("INSERT INTO users (name,email,user_name) VALUES (?,?,?)");
            $stmt->execute([$name, $email, "User" . $id]);
            $user_id = $myPdo->lastInsertId();

            $stmt = $myPdo->prepare("INSERT INTO accounts (user_id,type,google_id) VALUES (" . $myPdo->lastInsertId() . ",'google','" . $id . "')");
            $stmt->execute();

            $stmt = $myPdo->prepare("INSERT INTO logins (account_id) VALUES (" . $myPdo->lastInsertId() . ")");
            $stmt->execute();
        }
        else{
            $stmt = $myPdo->prepare("INSERT INTO logins (account_id) VALUES ((SELECT id from accounts where google_id=?))");
            $stmt->execute([$id]);
        }
        session_start();
        $_SESSION['userName'] = "User" . $id;
        header("Location: dashboard.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}