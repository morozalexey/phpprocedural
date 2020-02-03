<?php 
require_once 'init.php';

$id = $_GET['id'];

$show_comment = "DELETE FROM comments WHERE comments.id = ?";
$stmt_show_comment = $pdo->prepare($show_comment);
$stmt_show_comment->execute([$id]);
			
Location: header('Location: /admin.php');




?>