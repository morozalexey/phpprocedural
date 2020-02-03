<?php 
require_once 'init.php';

$form = $_POST;
$text = $_POST['text'];
$name = $_SESSION['user']['name'];
$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
    if (empty($text)){
        $_SESSION ['textErrorMessage'] = true;
    }
    if (empty($name)){
        $_SESSION ['nameErrorMessage'] = true;
    }
    if (!empty($text) && !empty($name)){
    $sql = "INSERT INTO comments (name, text, user_id, show_comment) VALUES (:name, :text, :user_id, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $_SESSION ['messageSuccess'] = true;
    
    }
	Location: header('Location: /index.php');
}



?>