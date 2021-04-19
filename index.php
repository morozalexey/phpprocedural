<?php
require_once 'init.php';
require_once 'authorization.php';

$sql = "SELECT comments.dt_add, comments.name, comments.text, users.avatar FROM comments, users WHERE users.id = comments.user_id AND show_comment=1 ORDER BY comments.dt_add DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['messageSuccess'])) {
    $messageSuccess = "<div class=\"alert alert-success\" role=\"alert\">Комментарий успешно добавлен </div>";
  unset($_SESSION['messageSuccess']);
}

if (isset($_SESSION ['textErrorMessage'])){
    $textErrorMessage = "<div class=\"alert alert-danger\" role=\"alert\">Это поле надо заполнить</div>";
    unset($_SESSION['textErrorMessage']);
}

if (isset($_SESSION ['nameErrorMessage'])){
    $nameErrorMessage = "<div class=\"alert alert-danger\" role=\"alert\">Это поле надо заполнить</div>";
    unset($_SESSION['nameErrorMessage']);
}

$user_id = isset($_SESSION['user']['id']);

$admin_role = $pdo->prepare("SELECT * FROM users WHERE id =?");
$admin_role->execute([$user_id]); 
$admin_result = $admin_role->fetch(PDO::FETCH_ASSOC);

?>

<pre><?php //var_dump($_SESSION);?></pre>

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
                        <!-- Authentication Links -->
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
                            <div class="card-header"><h3>Комментарии</h3></div>

                            <div class="card-body">
                              <?= isset($messageSuccess); ?>
							<?php foreach($result as $comment):?>								  
                                <div class="media">								
                                  <img src="<?= $comment['avatar']?>" class="mr-3" alt="..." width="64" height="64">
                                  <div class="media-body">
                                    <h5 class="mt-0"><?= $comment['name']?></h5> 								
                                    <span><small><?= date("d/m/Y", strtotime($comment['dt_add']))?></small></span>								
                                    <p><?= $comment['text']?></p>								
                                  </div>
                                </div>	
							<?php endforeach; ?>	
							
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-12" style="margin-top: 20px;">
                        <div class="card">
                            <div class="card-header"><h3>Оставить комментарий</h3></div>

                            <div class="card-body">
                                <form action="add-comment.php" method="post">
                                <?php if (!isset($_SESSION['user'])): ?>
                                <div class="alert alert-success" role="alert">Чтобы оставлять комментарии <a href="login.php">авторизуйтесь</a> </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Имя</label>
                                        <input name="name" class="form-control" id="exampleFormControlTextarea1" />
                                        <p><?= isset($nameErrorMessage); ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Сообщение</label>
                                        <textarea name="text" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        <p><?= isset($textErrorMessage) ; ?></p>
                                    </div>
                                    <div class="btn btn-success">Отправить</div>
                                <?php else: ?>
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Сообщение</label>
                                        <textarea name="text" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        <p><?= isset($textErrorMessage) ; ?></p>
                                    </div>
                                    <button type="submit" class="btn btn-success">Отправить</button>
                                <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

<?php  ?>