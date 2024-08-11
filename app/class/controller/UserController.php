<?php
namespace App\class\controller;

use App\class\entity\Usuario;
use Exception;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\class\DateFormater;
use App\class\controller\InscricoesController;


final class UserController extends Controller
{
    public static function view($id)
    {
        parent::isLoggedIn();
        parent::isAllowed($id);
        $usr = new Usuario();
        $usuario = $usr->getUserById($id);
        $insC = new InscricoesController();
        $inscricoes = $insC->getAllIncricoesByUserId($id);

        // echo '<pre>'; print_r($inscricoes); echo '</pre>';
        // exit;
        try {
            $loader = new FilesystemLoader('views/');
            $twig = new Environment($loader);
            $usuario->nome_usuario = $usuario->primeiro_nome.' '.$usuario->sobrenome;
            $usuario->created_at = DateFormater::simpleData($usuario->created_at);

            foreach ($inscricoes as $inscricao){
                // Formatacao da data de inicio
                $inscricao->nome_organizador = $inscricao->primeiro_nome.' '.$inscricao->sobrenome;
                
                $newDateInicio = DateFormater::data($inscricao->data_inicio);
                $newHoraInicio = DateFormater::hora($inscricao->data_inicio);
                $inscricao->data_inicio = $newDateInicio;
                $inscricao->hora_inicio = $newHoraInicio;
                
                // Formatacao da data de termino
                $newDateFim = DateFormater::data($inscricao->data_fim);
                $newHoraFim = DateFormater::hora($inscricao->data_fim);
                $inscricao->data_final = $newDateFim;
                $inscricao->hora_final = $newHoraFim;
            }

            $view = $twig->load("dadosUsuario.html");
            $data =[
                'usuario' => $usuario,
                'inscricoes' => $inscricoes
            ];

            $renderedContent = $view->render($data);
            echo $renderedContent;

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    public static function createUser($data, $files)
    {
        $user = new Usuario();
   
        $isUserCreated = $user->insertUser($data, $files);
        return $isUserCreated;
    }
    public static function edit($id)
    {
        parent::isLoggedIn();
        try {
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $renderedData = array();

            $view = $twig->load("edit.html");

            $data =  (object) array(
                "user_pic" => $_SESSION['profile_pic'],
                "username" => $_SESSION['username'],
            );
            $renderedData = [
                'user' => $data,
                // "posts" => $posts
            ];
            $renderedContent = $view->render($renderedData);
            echo $renderedContent;

        } catch(Exception $ex) {
            echo $ex->getMessage();
        }

    }
}
?>