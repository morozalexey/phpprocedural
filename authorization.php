<?php 

if(isset($_COOKIE["password_cookie_token"]) && !empty($_COOKIE["password_cookie_token"])){
 
    $user_cookie = "SELECT email, password FROM `users` WHERE password_cookie_token = '".$_COOKIE["password_cookie_token"]."'";
	$stmt_user_cookie = $pdo->prepare($user_cookie);
	$stmt_user_cookie->execute([$_COOKIE["password_cookie_token"]]);
	$user_cookie_result = $stmt_user_cookie->fetchAll(PDO::FETCH_ASSOC);
	
	if($user_cookie_result){
        $_SESSION['email'] = $$user_cookie_result["email"];
        $_SESSION['password'] = $$user_cookie_result["password"];
	}
}