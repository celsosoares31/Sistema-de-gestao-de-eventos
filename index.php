<?php 
session_start();
require __DIR__.'/vendor/autoload.php';

use App\class\controller\Login;

if (isset($_SESSION['id'])) {
    header('Location: ./app/');
    exit;
}
    
$formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

 if (!empty($formData['btnEntrar'])) {
    Login::index($formData);
 }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="./app/assets/bootstrap-5.3.2-dist/css/bootstrap.css" />
    <link rel="stylesheet" href="./app/assets/fontawesome-free-6.4.2-web/css/all.min.css" />
    <link rel="stylesheet" href="./app/assets/css/style.css">
    <title>Login</title>
</head>

<body class="bg-light body">
    <div class="bg-light rounded-5 shadow login-form">
        <div class="w-100 mt-5 d-flex justify-content-center">
            <img src="app/assets/img/logo.jpg" class="img-fluid mx-auto" />
        </div>
        <form class="form p-5 w-75 mx-auto" method="post" action="index.php">
            <div class="form-group p-2">
                <div class="col-sm-12">
                    <input class="form-control " name="email" placeholder="E-mail" type="email" required />
                </div>
            </div>
            <div class="form-group p-2">
                <div class="col-sm-12">
                    <input class="form-control " name="senhaInput" placeholder="Senha" type="password" required />
                </div>
            </div>
            <div class="row p-2 mb-4 mt-2 pl-4">
                <div class="col-lg-6">
                </div>
                <div class="col-sm-12 col-lg-6">
                    <input type="submit" class="btn bg-1 fs-5 w-100" name="btnEntrar" value="Entrar" />
                </div>
            </div>
            <div class="px-2 w-100 mb-0">
                <p class="fs-6">
                    Nao possui uma conta?
                    <a class="link-offset-2 link-underline link-underline-opacity-0" href="registar.php">
                        Registar</a>
                </p>
            </div>
        </form>
    </div>
    <script src="./app/assets/fontawesome-free-6.4.2-web/js/all.js">
    </script>
    <script src="./app/assets/bootstrap-5.3.2-dist/js/bootstrap.js">
    </script>
    <script src="./app/assets/js/script.js"></script>
</body>