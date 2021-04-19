<?php
require_once 'init.php';
require_once 'authorization.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $form = $_POST;
    $required = ['email', 'password'];
    $errors = [];
    
    foreach ($required as $field) {
	    if (empty($form[$field])) {
	        $errors[$field] = 'Это поле надо заполнить';
        }
    }

    if(filter_var($form['email'], FILTER_VALIDATE_EMAIL) === false) { 
        $errors['email'] = "Формат почтового адреса неверный";
    } 

    if(!count($errors)){
        $email = $form['email'];
        $stmt = $pdo->prepare("SELECT users.id, users.name, users.email, users.password, users.avatar, users.password_cookie_token FROM users WHERE email =?");
        $stmt->execute([$email]); 
        $user = $stmt ? $stmt->fetch() : null;
        
        if(!$user){
            $errors['email'] = 'Такой пользователь не найден';
        } 
        if(!password_verify($form['password'], $user['password'])) {
            $errors['password'] = 'Неверный пароль';
        }
        else{
            $_SESSION['user'] = $user;            
        }    
    }

    if(isset($_POST["remember"])){

        $email = $form['email'];
		$password = $form['password'];
        $password_cookie_token = md5(isset($array_user_data["id"]).$password.time());
    
        $stmt_token = $pdo->prepare("UPDATE `users` SET `password_cookie_token` = '".$password_cookie_token."' WHERE email = '".$email."'");
        $stmt_token->execute([$password_cookie_token, $email]); 
        $_SESSION['user']['password_cookie_token'] = $password_cookie_token;        
				
        setcookie("password_cookie_token", $password_cookie_token, time() + (1000 * 60 * 60 * 24 * 30));
    
    }else{        
        if(isset($_COOKIE["password_cookie_token"])){
            $update_password_cookie_token = $mysqli->query("UPDATE users SET password_cookie_token = '' WHERE email = $email");
            setcookie("password_cookie_token", "", time() - 3600);
        }            
    }
    header("Location: /index.php");
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
                            <div class="card-header">Login</div>

                            <div class="card-body">
                                <form method="POST" action="">                                    

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="text" class="form-control <?= isset($errors['email']) ? "is-invalid" : "" ; ?>" name="email"  autocomplete="email" autofocus value="<?= isset($email); ?>">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong><?= isset($errors['email']) ? $errors['email'] : "" ; ?></strong>
                                                </span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control <?= isset($errors['password']) ? "is-invalid" : "" ; ?>" name="password"  autocomplete="current-password">
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?= isset($errors['password']) ? $errors['password'] : "" ; ?></strong>
                                            </span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                                                <label class="form-check-label" for="remember">
                                                    Remember Me
                                                </label>                                               
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                               Login
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