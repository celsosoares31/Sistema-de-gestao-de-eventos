<?php
require_once '../vendor/autoload.php';
include_once './functions/Toast.php';

session_start();

use App\class\controller\EventoController;
use App\core\Core;
use App\class\controller\InscricoesController;

if (!isset($_SESSION['id'])) {
    if(time() - $_SESSION["login_time_stamp"] > 600)
    {
        session_unset();
        session_destroy();
        header("Location:login.php");
    }else{
        header('Location: ../');
        exit; 
    }
}   
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    ob_start();
    Core::init($_GET);  
    $outPut = ob_get_contents();
    ob_end_clean();
    renderLayout($outPut);
}  
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($formData['participar'])){
        extract($formData);
      if(isset($_SESSION['participar']) && $_SESSION["participar"] == $_POST['evento_id']){
            header('location: index.php');
            exit;     
        }else {
            $_SESSION['participar'] = $_POST['evento_id'];

            InscricoesController::participar($participante_id, $evento_id);
        }
    }
    if(!empty($formData['eventoPost'])){
        if(isset($_SESSION['data_hora_inicio']) && $_SESSION["data_hora_inicio"] == $_POST['data_hora_inicio']){
            header('location: index.php?page=evento&action=view&id='.$_SESSION['id']);
            exit;     
        }else {
            $_SESSION['data_hora_inicio'] = $_POST['data_hora_inicio'];
            $file = $_FILES["imagem"];
            $evento = new EventoController();
          
            $evento->insert($formData, $file);  
            if($evento){
                echo "<script>alert('Evento Cadastrado com sucesso!!');</script>";
                header('refresh: 0.01; url=index.php?page=evento&action=view&id='.$_SESSION['id']);
                exit;
            }else{
                echo "<script>alert('Ocorreu algum erro ao tentar cadstrar o evento');</script>";  
                header('refresh: 0.01; url=index.php?page=evento&action=view&id='.$_SESSION['id']);
                exit;
            }     
        }  
    }
    if(!empty($formData['eventoActualizar'])){
        $file = $_FILES["imagem"];
        $evento = new EventoController();
    
        $isPostUpdated = $evento->updateEvent($formData, $file);
        if($isPostUpdated) {
            echo "<script>alert('Actualização feita com sucesso!!');</script>";      
            header('refresh: 0.01; url=index.php?page=evento&action=view&id='.$_SESSION['id']);  
            exit; 
        }
    }
}
function renderLayout($content)
{   $actual_user = "";
    if(isset($_SESSION['id'])){
        $actual_user = $_SESSION['username'];
        $actual_user_img = $_SESSION['profile_pic'];
        $actual_id = $_SESSION['id']; 
      
    }

    $layout_path = "./views/layout.html";
    $layout = file_get_contents($layout_path);
    
    $updatedLayout = str_replace(["{{activeUser}}", "{{activeUserImg}}","{{activeId}}","{{pageContent}}"], [$actual_user,$actual_user_img,$actual_id,$content], $layout);
    
    echo $updatedLayout;
}
?>