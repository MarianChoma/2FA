<?php
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

include_once 'vendor/google-authenticator/FixedBitNotation.php';
include_once 'vendor/google-authenticator/GoogleAuthenticatorInterface.php';
include_once 'vendor/google-authenticator/GoogleAuthenticator.php';
include_once 'vendor/google-authenticator/GoogleQrUrl.php';

$g = new GoogleAuthenticator();
$secret = $g->generateSecret();
session_start();
$_SESSION['secret'] = $secret;

header("Location: check.php");
