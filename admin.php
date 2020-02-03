<?php 
require_once 'init.php';
require_once 'authorization.php';

$sql = "SELECT users.avatar, users.name, comments.id, comments.dt_add, comments.text, comments.show_comment FROM comments, users WHERE comments.user_id = users.id ORDER BY comments.dt_add DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user_id = $_SESSION['user']['id'];

$admin_role = $pdo->prepare("SELECT * FROM users WHERE id =?");
$admin_role->execute([$user_id]); 
$admin_result = $admin_role->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comments</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    Project
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                    <?php if (!isset($_SESSION['user'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
						<?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="profile.php"><?=strip_tags($_SESSION['user']['name']); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>                            				
						<?php endif; ?>
						<?php if ( isset($_SESSION['user']) AND ($admin_result['admin'] == '0') ): ?>
                        <?php elseif ( isset($_SESSION['user']) AND ($admin_result['admin'] == 'admin') ) : ?>
						<li class="nav-item">
							<a class="nav-link" href="admin.php">Admin</a>
                        </li>
						<?php endif; ?>	
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h3>Админ панель</h3></div>

                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Аватар</th>
                                            <th>Имя</th>
                                            <th>Дата</th>
                                            <th>Комментарий</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>

                                    <tbody>
									<?php foreach($result as $comment):?>
                                        <tr>										
                                            <td>
                                                <img src="<?= $comment['avatar'] ?>" alt="" class="img-fluid" width="64" height="64">
                                            </td>
                                            <td><?= $comment['name'] ?></td>
                                            <td><?= $comment['dt_add'] ?></td>
                                            <td><?= $comment['text'] ?></td>
                                            <td>	
												<?php if ($comment['show_comment'] != 1) : ?>
                                                <a href="show-comment.php?id=<?= $comment['id'] ?>" class="btn btn-success">Разрешить</a>
												<?php else : ?>
                                                <a href="hide-comment.php?id=<?= $comment['id'] ?>" class="btn btn-warning">Запретить</a>
												<?php endif ; ?>
                                                <a href="delete-comment.php?id=<?= $comment['id'] ?>" onclick="return confirm('are you sure?')" class="btn btn-danger">Удалить</a>
                                            </td>											
                                        </tr>
									<?php endforeach; ?>									
									
									
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
