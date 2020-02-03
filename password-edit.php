<?php
require_once 'init.php';
require_once 'authorization.php';

$email = $_SESSION['user']['email'];
$user_id = $_SESSION['user']['id']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
	$required_fields = ['current', 'password', 'password_confirmation'];
	
    $passChange = array_intersect_key($_POST, array_flip($required_fields));
    
    $_SESSION['errors'] = [];

    foreach ($required_fields as $field) {
        if (empty($passChange[$field])) {
            $_SESSION['errors'][$field] = 'Поле не заполнено';
        }
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id =?");
    $stmt->execute([$user_id]); 
    $user = $stmt ? $stmt->fetch() : null;

    if (!password_verify($_POST['current'], $user['password'])) {
        $_SESSION['errors']['current'] = 'Неверный пароль';
    }

    if($_POST['password'] != $_POST['password_confirmation']){
        $_SESSION['errors']['password'] = 'Эти поля должны совпадать';
        $_SESSION['errors']['password_confirmation'] = 'Эти поля должны совпадать';
    }

    if (empty($_POST['password']) && (strlen($_POST['password']) <= 6)) {
        $_SESSION['errors']['password'] = 'Пароль не должен быть короче 6 символов';
    }
        
  
    if(empty($_SESSION['errors'])){ 
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);;
        
        $sql_password_change = "UPDATE users SET password = ? WHERE id = ?";
        $stmt_password_change = $pdo->prepare($sql_password_change);
        $stmt_password_change->execute([$newPassword, $user_id]);

        $_SESSION ['messagePasswordSucces'] = true;
			
    } else {
        $_SESSION ['messagePasswordError'] = true;        
    }  

	Location: header('Location: /profile.php');

}