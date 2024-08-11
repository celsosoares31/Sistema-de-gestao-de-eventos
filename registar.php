<?php 
session_start();
require __DIR__.'/vendor/autoload.php';
include_once './app/functions/Toast.php';

use App\class\controller\UserController;
   
$formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

 if (!empty($formData['regUser'])) {
    if(isset($_SESSION['email']) && $_SESSION["email"] == $_POST['email']){
       header('location: registar.php');
       exit;
    }else {
        $_SESSION['email'] = $_POST['email'];        
    }
      
    $userC = new UserController();
    $file = $_FILES['profile_pic'];

    $userC->createUser($formData, $file);
    if($userC){
        printError('Usuario inserido com sucesso', 'success');
        header('refresh:2; url=index.php');
    }else{
        printError('Ocorreu um erro ao inserir os dados', 'danger');
        header('refresh:1.5;');
    }
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
    <title>Cadastrar Usuario</title>
</head>

<body class="bg-light body">
    <div class="w-50 shadow p-3 rounded-5">
        <a href="index.php" class="fs-4 mt-4 ms-5"><i class="fa-solid fa-arrow-left fs-2 text-body-secondary"></i></a>
        <div class="w-100 d-flex justify-content-center">
            <img src="app/assets/img/logo-nobg.png" class="img-fluid mx-auto" />
        </div>

        <h1 class="fs-4 mt-3 ms-5">Cadastrar-se</h1>
        <form class="mt-5 mx-5" id="form" method="POST" enctype="multipart/form-data" action="registar.php">
            <div class="mb-2 ">
                <label for="primeiro_nome" class="form-label">Nome:</label>
                <input class="form-control" type="text" name="primeiro_nome" id="primeiro_nome" required />
            </div>

            <div class="mb-2 ">
                <label for="sobrenome" class="form-label">Sobrenome:</label>
                <input class="form-control" type="text" name="sobrenome" id="sobrenome" required />
            </div>

            <div class="mb-2 ">
                <label for="email" class="form-label">Email:</label>
                <input class="form-control" type="email" name="email" id="email" required />
            </div>

            <div class="mb-2 ">
                <label for="senha" class="form-label">Senha</label>
                <input class="form-control" type="password" name="senha" id="senha" required />
            </div>

            <div class="mb-2 ">
                <label for="imagem" class="form-label">Imagem de perfil:</label>
                <input class="form-control" type="file" name="profile_pic" required />
            </div>

            <div class="mb-2 ">
                <input class="btn btn_bg my-4" type="submit" value="Cadastrar Usuario" name="regUser" />
            </div>

        </form>
    </div>

    <script src="./app/assets/fontawesome-free-6.4.2-web/js/all.js">
    </script>
    <script src="./app/assets/bootstrap-5.3.2-dist/js/bootstrap.js">
    </script>
    <script src="./app/assets/js/script.js"></script>
</body>