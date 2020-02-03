<?php
require_once 'init.php';
require_once 'authorization.php';

$email = $_SESSION['user']['email'];
$user_id = $_SESSION['user']['id']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
	$required_fields = ['name', 'email'];
	
    $user = array_intersect_key($_POST, array_flip($required_fields));
    
    $_SESSION['errors'] = [];
    
    foreach ($required_fields as $field) {
        if (empty($user[$field])) {
            $_SESSION['errors'][$field] = 'Поле не заполнено';
        }
    }    
    
    if(!empty($user['email']) && filter_var($user['email'], FILTER_VALIDATE_EMAIL) === false) { 
        $_SESSION['errors']['email'] = "Формат почтового адреса неверный";
    } 
         
    if (!empty($_FILES['image']['tmp_name'])){

        $finfo = mime_content_type($_FILES['image']['tmp_name']);
        $file_name = $_FILES['image']['tmp_name'];
        if ($finfo != 'image/jpeg' && $finfo != 'image/png') {
            $_SESSION['errors']['image'] = 'Загрузите картинку в формате JPG или PNG';
        }

        $avatar_old = $pdo->prepare("SELECT users.avatar FROM users WHERE id =?");
        $avatar_old->execute([$user_id]); 
        $avatar_old_result = $avatar_old->fetch(PDO::FETCH_ASSOC); 

        if($avatar_old_result['avatar'] != NULL){
            unlink($avatar_old_result['avatar']);
        }
         
        if(empty($_SESSION['errors']['image']) && isset($finfo)){
        
            $extensionsByMimeType = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png'
            ];
            
            $filename = uniqid() . '.' . $extensionsByMimeType[$finfo] ;
            $path = 'img/' . $filename; 
            move_uploaded_file($_FILES['image']['tmp_name'], $path);
            
            $_POST['image'] = $path;
            
            $avatar = $_POST['image'];
            $newName = $_POST['name'];
            $newEmail = $_POST['email'];
			
            $stmt_avatar = "UPDATE users SET name = ?,  email =?, avatar = ? WHERE id = ?";
            $stmt_avatar = $pdo->prepare($stmt_avatar);
            $stmt_avatar->execute([$newName, $newEmail, $avatar, $user_id]);

            $_SESSION['user']['email'] = $newEmail;
            $_SESSION['user']['name'] = $newName;
            $_SESSION['user']['avatar'] = $avatar;

            $_SESSION ['messageProfileSucces'] = true;
        }
        else{
            exit();            
        }
    }
    elseif(empty($_SESSION['errors'])){ 
        $newName = $_POST['name'];
        $newEmail = $_POST['email'];
        $sql_profile_info = "UPDATE users SET name = ?,  email =? WHERE id = ?";
        $stmt_profile_info = $pdo->prepare($sql_profile_info);
        $stmt_profile_info->execute([$newName, $newEmail, $user_id]);

        $_SESSION['user']['email'] = $newEmail;
        $_SESSION['user']['name'] = $newName;

        $_SESSION ['messageProfileSucces'] = true;
			
    } else {
        $_SESSION ['messageProfileError'] = true;        
    }     

	Location: header('Location: /profile.php');
    
}