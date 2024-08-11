<?php
header('Content-Type: application/json');
require '../../../vendor/autoload.php';
use App\class\entity\Evento;

$pesquisar = $_GET['text'];
$evento = new Evento();
$eventos = $evento->searchEvents($pesquisar);

$resp = new stdClass();

if (!$eventos) {
    $resp->status = false;
} else {
    $resp->status = true;
    $resp->dados = $eventos;
}

$respJSON = json_encode($resp);
echo $respJSON;
