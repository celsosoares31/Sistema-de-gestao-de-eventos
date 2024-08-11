<?php
namespace App\class\controller;

include_once "./app/functions/Toast.php";

use App\class\entity\Usuario;

final class Login{

    public static function index(array $formData){
        extract( $formData);
            $user = new Usuario();
            $isLoggedIn = $user->getUserByEmail($email);

            if(!isset($isLoggedIn['errorMsg'])) {
                extract($isLoggedIn);

                if (password_verify($senhaInput, $senha)) {
                    $username = $primeiro_nome.' '.$sobrenome;
                    $_SESSION['id'] = $id;
                    $_SESSION['profile_pic'] = $profile_pic;
                    $_SESSION['username'] = $username;
                    $_SESSION["login_time_stamp"] = time();

                    header('Location: ./app/');
                    
                } else {
                    printError('Usuario ou senha invalidos', 'danger');
                    header('refresh:3; ');
                }
            } else {
                printError($isLoggedIn['errorMsg'], 'danger');
                header('refresh:3; ');
            }
        
    }
}