<?php 
require_once 'init.php';

$id = $_GET['id'];

$show_comment = "UPDATE comments SET show_comment = 0 WHERE id = ?";
$stmt_show_comment = $pdo->prepare($show_comment);
$stmt_show_comment->execute([$id]);
			
Location: header('Location: /admin.php');




?>