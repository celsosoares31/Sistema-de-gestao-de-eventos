<?php
namespace App\class\controller;

include_once "./functions/Toast.php";

use App\class\controller\Controller;
use App\class\DateFormater;
use App\class\entity\Evento;
use App\class\entity\Inscricao;
use App\class\entity\Usuario;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class EventoController extends Controller
{
    /**
     * @param $id
     * @returns retorna uma view de registos de cada usuario
     */
    public static function view($id)
    {
        parent::isAllowed($id);
        parent::isLoggedIn();

        $evento = new Evento();
        $eventosConcluidos = $evento->getConcludedEventsById($id);
        $eventosActivos = $evento->getActiveEventsById($id);

        foreach ($eventosActivos as $ev) {
            $inscricao = new Inscricao();
            $sumInscritos = $inscricao->getTotalInscricoesPerEvent($ev->evento_id);
            if (count($sumInscritos) > 0) {
                $ev->totalInscritos = $sumInscritos[0]->total;
            } else {
                $ev->totalInscritos = 0;
            }

            $ev->organizador = $ev->primeiro_nome . ' ' . $ev->sobrenome;

            // Formatacao da data de inicio
            $formatedDateInicio = DateFormater::data($ev->data_inicio);
            $formatedHoraInicio = DateFormater::hora($ev->data_inicio);
            $ev->data_inicio = $formatedDateInicio;
            $ev->hora_inicio = $formatedHoraInicio;

            // Formatacao da data de termino
            $formatedDateFim = DateFormater::data($ev->data_fim);
            $formatedHoraFim = DateFormater::hora($ev->data_fim);
            $ev->data_fim = $formatedDateFim;
            $ev->hora_fim = $formatedHoraFim;
        }

        foreach ($eventosConcluidos as $ev) {
            $ev->organizador = $ev->primeiro_nome . ' ' . $ev->sobrenome;

            // Formatacao da data de inicio
            $formatedDateInicio = DateFormater::data($ev->data_inicio);
            $formatedHoraInicio = DateFormater::hora($ev->data_inicio);
            $ev->data_inicio = $formatedDateInicio;
            $ev->hora_inicio = $formatedHoraInicio;

            // Formatacao da data de termino
            $formatedDateFim = DateFormater::data($ev->data_fim);
            $formatedHoraFim = DateFormater::hora($ev->data_fim);
            $ev->data_fim = $formatedDateFim;
            $ev->hora_fim = $formatedHoraFim;
        }
        try {
            $loader = new FilesystemLoader('views/');
            $twig = new Environment($loader);

            $user = [
                'id' => $_SESSION['id'],
                'name' => $_SESSION['username'],
            ];

            $view = $twig->load("registarEventos.html");
            $renderData = [
                'organizador' => (object) $user,
                'eventosConcluidos' => $eventosConcluidos,
                'eventosActivos' => $eventosActivos,
            ];
            $renderedContent = $view->render($renderData);
            echo $renderedContent;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public static function calendario($id)
    {
        parent::isLoggedIn();
        $id = parent::isAllowed($id);

        try {
            $loader = new FilesystemLoader('views/');
            $twig = new Environment($loader);

            $view = $twig->load("calendario.html");

            $renderedContent = $view->render();
            echo $renderedContent;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public static function edit($id)
    {
        parent::isLoggedIn();
        try {

            $loader = new FilesystemLoader('views/');
            $twig = new Environment($loader);
            $renderedData = array();

            $view = $twig->load("edit.html");
            $ev = new Evento();

            $evento = $ev->getEventById($id);

            $evento->organizador = $evento->primeiro_nome . ' ' . $evento->sobrenome;
            $evento->organizador_id = $evento->id;

            $renderedData = [
                'evento' => $evento,
            ];

            $renderedContent = $view->render($renderedData);
            echo $renderedContent;

        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

    }
    public function delete($id)
    {
        parent::isLoggedIn();
        $evento = new Evento();
        $isDeleted = $evento->deleteEvent($id);

        if ($isDeleted) {
            $usrImagesDir = "images/eventos/$id";
            if (file_exists($usrImagesDir)) {
                parent::rrmdir($usrImagesDir);
                header("Location: index.php?page=evento&action=view&id=" . $_SESSION['id']);
            }
        }
    }
    public function insert($data, $file)
    {
        parent::isLoggedIn();
        $evento = new Evento();
        $isInserted = $evento->insertEvent($data, $file);

        if (!$isInserted) {
            return false;
        } else {

            return true;
        }

    }
    public function updateEvent($data, $file)
    {
        parent::isLoggedIn();
        $post = new Evento();
        $isPostUpdated = $post->update($data, $file);

        return $isPostUpdated;
    }

}
