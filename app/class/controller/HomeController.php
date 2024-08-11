<?php
namespace App\class\controller;

use Exception;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\class\entity\Evento;
use App\class\entity\Usuario;
use App\class\DateFormater;
use App\class\controller\InscricoesController;
use App\class\controller\Controller;

class HomeController extends Controller
{
    public static function index()
    {
        parent::isLoggedIn();
        $evento = new Evento();
        $eventosActualizacao = $evento->getAllEvents();
        
        $now = date('Y-m-d H:i:s');
        foreach ($eventosActualizacao as $event){    
            if($now > $event->data_fim){
                $evento = new Evento();
                $evento->concluirEvento($event->evento_id);
            }
        }
        $eventos = $evento->getAllEvents();
        foreach ($eventos as $ev){
            $inscricaoC = new InscricoesController();
            $inscricao = $inscricaoC->getAllIncricoesByPostId($ev->evento_id);
            $ev->inscricoes = $inscricao;

            $ev->organizador_id = $_SESSION['id'];
            $ev->organizador = $ev->primeiro_nome.' '.$ev->sobrenome;
            
            $now = date('Y-m-d H:i:s');

            if($now > $ev->data_fim){
                $evento->concluirEvento($ev->evento_id);
            }

            // Formatacao da data de inicio
            $newDateStart = DateFormater::data($ev->data_inicio);
            $newHoraStart = DateFormater::hora($ev->data_inicio);
            $ev->data_inicio = $newDateStart;
            $ev->hora_inicio = $newHoraStart;

            // Formatacao da data de termino
            $newDateFim = DateFormater::data($ev->data_fim);
            $newHoraFim = DateFormater::hora($ev->data_fim);
            $ev->data_fim = $newDateFim;
            $ev->hora_fim = $newHoraFim;
        }

        try {
            $loader = new FilesystemLoader('views/');
            $twig = new Environment($loader, ['auto_reload'=>true]);

            $view = $twig->load("home.html");
            $renderData = [
                'eventos' => $eventos,
            ];

            $renderedContent = $view->render($renderData);
            echo $renderedContent;

        } catch(Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public static function view($id)
    {
        parent::isLoggedIn();
        $ev = new Evento();
        $evento = $ev->getEventById($id);
       
        $inscricaoC = new InscricoesController();
        $inscricao = $inscricaoC->getAllIncricoesByPostId($evento->evento_id);
        $evento->inscricoes = $inscricao;

        $evento->organizador_id = $_SESSION['id'];
        $evento->organizador = $evento->primeiro_nome.' '.$evento->sobrenome;
        
        $newDateInicio = DateFormater::data($evento->data_inicio);
        $newHoraInicio = DateFormater::hora($evento->data_inicio);
        $evento->data_inicio = $newDateInicio;
        $evento->hora_inicio = $newHoraInicio;

        $newDateFim = DateFormater::data($evento->data_fim);
        $newHoraFim = DateFormater::hora($evento->data_fim);
        $evento->data_fim = $newDateFim;
        $evento->hora_fim = $newHoraFim;
        
        try {
            $loader = new FilesystemLoader('views/');
            $twig = new Environment($loader);

            $view = $twig->load("viewSingle.html");
            $renderData = [
                'evento' => $evento,
            ];

            $renderedContent = $view->render($renderData);
            echo $renderedContent;

        } catch(Exception $ex) {
            echo $ex->getMessage();
        }
    }
}