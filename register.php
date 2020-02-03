<?php
require_once 'init.php';
require_once 'authorization.php';

$user = $_POST;
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$password_confirmation = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);

$required_fields = ['name', 'email', 'password', 'password_confirmation'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = array_intersect_key($_POST, array_flip($required_fields));
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($user[$field])) {
        $errors[$field] = 'Это поле надо заполнить';
        }
    }

    if(filter_var($user['email'], FILTER_VALIDATE_EMAIL) === false) { 
        $errors['email'] = "Формат почтового адреса неверный";
    }  


    $stmt = $pdo->prepare("SELECT * FROM users WHERE email =?");
    $stmt->execute([$email]); 
    $checkUser = $stmt->fetch();

    if ($checkUser['email'] == $user['email']){
        $errors['email'] = "Пользователь с таким email уже зарегистрирован";
    }
    
    
    if($_POST['password'] != $_POST['password_confirmation']){
        $errors['password'] = 'Эти поля должны совпадать';
        $errors['password_confirmation'] = 'Эти поля должны совпадать';
    }

    if (strlen($_POST['password']) <= 6) {
        $errors['password'] = 'Пароль не должен быть короче 6 символов';
    }

    if(empty($errors)){
    $sql = "INSERT INTO users (name, email, password, dt_add, avatar, admin) VALUES (:name, :email, :password, NOW(), "img/no-user.jpg", 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    Location: header('Location: /login.php');   
    
    }    
}

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
                        <!-- Authentication Links -->
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Register</div>

                            <div class="card-body">
                                <form method="POST" action="">

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control <?= isset($errors['name']) ? "@error('name') is-invalid @enderror" : "" ; ?>" name="name" autofocus value="<?= $name ?>">
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?= isset($errors['name']) ? $errors['name'] : "" ; ?></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="text" class="form-control <?= isset($errors['email']) ? "@error('name') is-invalid @enderror" : "" ; ?>" name="email" value="<?= $email ?>">
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?= isset($errors['email']) ? $errors['email'] : "" ; ?></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control <?= isset($errors['password']) ? "@error('name') is-invalid @enderror" : "" ; ?>" " name="password"  autocomplete="new-password">
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?= isset($errors['password']) ? $errors['password'] : "" ; ?></strong>
                                            </span>                                            
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control <?= isset($errors['password_confirmation']) ? "@error('name') is-invalid @enderror" : "" ; ?>" name="password_confirmation"  autocomplete="new-password">
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?= isset($errors['password_confirmation']) ? $errors['password_confirmation'] : "" ; ?></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                Register
                                            </button>
                                        </div>
                                    </div>
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