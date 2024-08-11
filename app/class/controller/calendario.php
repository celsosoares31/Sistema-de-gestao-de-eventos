<?php

require '../../../vendor/autoload.php';
use App\class\entity\Inscricao;
use App\class\entity\Evento;

session_start();

$currentUser = $_SESSION['id'];

$insc = new Inscricao();
$env = new Evento();

$eventos = $env->getEventsById($currentUser);

$eventosComInscricao = $insc->getInscricaoUserId($currentUser);

$result = [];

foreach ($eventosComInscricao as $key => $value) {
   $result[] = [
    'id'=>$value->evento_id,
    'color'=>$value->cor,
    'title' => $value->nome,
    'start'=>$value->data_inicio,
    'end'=>$value->data_fim,

   ];
}
foreach ($eventos as $key => $value) {
   $result[] = [
    'id'=>$value->evento_id,
    'color'=>$value->cor,
    'title' => $value->nome,
    'start'=>$value->data_inicio,
    'end'=>$value->data_fim,

   ];
}

echo json_encode($result);

?>