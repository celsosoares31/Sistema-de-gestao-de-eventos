<?php
namespace App\class\controller;

include_once "./functions/Toast.php";

use App\class\entity\Inscricao;
use App\class\controller\Controller;
use App\class\entity\Evento;

class InscricoesController extends Controller
{
    public static function getAllIncricoesByPostId($id)
    {
        parent::isLoggedIn();
        $inscricao = new Inscricao();

        $inscricoes = $inscricao->getInscricaoId($id);

        return $inscricoes;

    }
    public static function getAllIncricoesByUserId($id)
    {
        parent::isLoggedIn();
        $inscricao = new Inscricao();

        $inscricoes = $inscricao->getInscricaoUserId($id);

        return $inscricoes;

    }
    public static function participar($participante_id, $evento_id){

        $inscrito = new Inscricao();
        $ev = new Evento();

        $isregistado = $inscrito->getInscricaoId($evento_id);
        $evento = $ev->getEventById($evento_id);

        if($evento->organizador_id == $participante_id){
            echo "<script>alert('É o organizador deste evento, portanto, já é participante.');</script>";
            header('refresh: 0.001; url=index.php');
            return true;
            exit;
        }else{
           if($isregistado) {
                $isparticipante = false;
                foreach ($isregistado as $key => $value) {
                    if($value->participante_id == $participante_id) {
                        $isparticipante = true;
                        break;
                    } 
                }

                if($isparticipante){
                    echo "<script>alert('Ja esta participar deste evento.');</script>";
                    header('refresh: 0.001; url=index.php');
                    return true;
                    exit;
                } 
                $isInscrito = $inscrito->inscricao($participante_id, $evento_id);
                if($isInscrito){
                    echo "<script>alert('Aderiu com sucesso!!');</script>";
                    header('refresh: 0.001; url=index.php');
                    return true;
                    exit;
                }else{
                    echo "<script>alert('Ocorreu algum erro ao tentar aderir ao evento');</script>";
                    header('refresh: 0.001; url=index.php');
                    return true;
                    exit;
                }
           }else{  
                $isInscrito = $inscrito->inscricao($participante_id, $evento_id);
                if($isInscrito){
                    echo "<script>alert('Aderiu com sucesso!!');</script>";
                    header('refresh: 0.001; url=index.php');
                    return true;
                    exit;
                }else{
                    echo "<script>alert('Ocorreu algum erro ao tentar aderir ao evento');</script>";
                    header('refresh: 0.001; url=index.php');
                    return true;
                    exit;
                }

           }
        }
    }
}
?>