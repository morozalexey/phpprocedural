<?php
require_once 'init.php';

$_SESSION = [];

if(isset($_COOKIE["password_cookie_token"])){
	$email = $_SESSION["email"];    
	$stmt_clear_token = $pdo->prepare("UPDATE users SET password_cookie_token = '' WHERE email = ?");
    $stmt_clear_token->execute([$email]);    
		
    setcookie("password_cookie_token", "", time() - 3600);
}

header("Location: /");