<?php
declare (strict_types = 1);
namespace App\class\entity;

use App\class\config\DbConnect;
use App\class\controller\Controller;
use Exception;
use PDO;
use PDOException;

final class Evento extends Controller
{
    private $connection;
    private $tableName = 'eventos';

    public $evento_id;
    public $nome;
    public $background;
    public $data_inicio;
    public $hora_inicio;
    public $data_fim;
    public $hora_fim;
    public $local;
    public $cor;
    public int $totalInscritos;
    public $descricao;
    public $organizador_id;
    public $organizador;
    public $email;
    public $created_at;
    public $updated_at;
    public $inscricoes;
    public $estado_evento;

    public function getAllEvents()
    {
        $this->connection = DbConnect::getConnection();
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = 'SELECT evento_id, nome, organizador_id, estado_evento, background, descricao, data_inicio, data_fim, local, usuarios.primeiro_nome, usuarios.sobrenome FROM ' . $this->tableName . ' INNER JOIN usuarios ON usuarios.id = eventos.organizador_id WHERE estado_evento = "activo" ORDER BY nome DESC';
            $stmt = $this->connection->prepare($query);
            $resul = array();
            if ($stmt->execute()) {
                while ($row = $stmt->fetchObject(__CLASS__)) {
                    $resul[] = $row;
                }
            }
            DbConnect::closeConnection($this->connection);
            return $resul;
        } catch (PDOException $e) {
            return ['errorMsg' => $e->getMessage()];
        }
        $this->connection = null;
    }
    public function searchEvents($pesquisa)
    {
        $this->connection = DbConnect::getConnection();
        try {
            $newPesquisa = "\"%" . $pesquisa . "%\"";
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = 'SELECT * FROM eventos WHERE descricao LIKE ' . $newPesquisa . ' OR nome LIKE ' . $newPesquisa . ' OR local LIKE ' . $newPesquisa;
            $stmt = $this->connection->prepare($query);
            $result = array();
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $result[] = $row;
                }
            }

            DbConnect::closeConnection($this->connection);
            return $result;
        } catch (PDOException $e) {

            return $resul["error"] = $e->getMessage();
        }
        $this->connection = null;
    }
    public function getConcludedEventsById($id)
    {
        parent::isLoggedIn();
        parent::isAllowed($id);

        $this->connection = DbConnect::getConnection();
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $queryById = 'SELECT evento_id, nome, estado_evento, cor, organizador_id, data_inicio, data_fim, local, usuarios.primeiro_nome, usuarios.sobrenome FROM ' . $this->tableName . ' eventos INNER JOIN usuarios ON usuarios.id = eventos.organizador_id WHERE organizador_id=:id AND estado_evento="concluido" ORDER BY data_fim DESC';
            $stmt = $this->connection->prepare($queryById);
            $stmt->bindParam(":id", $id);
            $resul = array();
            if ($stmt->execute()) {
                while ($row = $stmt->fetchObject(__CLASS__)) {
                    $resul[] = $row;
                }
            }
            DbConnect::closeConnection($this->connection);

            return $resul;
        } catch (PDOException $e) {
            return ['errorMsg' => $e->getMessage()];
        }
        $this->connection = null;
    }
    public function getActiveEventsById($id)
    {
        parent::isLoggedIn();
        parent::isAllowed($id);

        $this->connection = DbConnect::getConnection();
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $queryById = 'SELECT evento_id, nome, estado_evento, background, cor, organizador_id, data_inicio, data_fim, local, usuarios.primeiro_nome, usuarios.sobrenome FROM ' . $this->tableName . ' eventos INNER JOIN usuarios ON usuarios.id = eventos.organizador_id WHERE organizador_id=:id AND estado_evento="activo" ORDER BY data_fim DESC';
            $stmt = $this->connection->prepare($queryById);
            $stmt->bindParam(":id", $id);
            $resul = array();
            if ($stmt->execute()) {
                while ($row = $stmt->fetchObject(__CLASS__)) {
                    $resul[] = $row;
                }
            }
            DbConnect::closeConnection($this->connection);

            return $resul;
        } catch (PDOException $e) {
            return ['errorMsg' => $e->getMessage()];
        }
        $this->connection = null;
    }
    public function getEventsById($id)
    {
        parent::isLoggedIn();
        parent::isAllowed($id);

        $this->connection = DbConnect::getConnection();
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $queryById = 'SELECT evento_id, nome, estado_evento, background, cor, organizador_id, data_inicio, data_fim, local, usuarios.primeiro_nome, usuarios.sobrenome FROM ' . $this->tableName . ' eventos INNER JOIN usuarios ON usuarios.id = eventos.organizador_id WHERE organizador_id=:id ORDER BY data_fim DESC';
            $stmt = $this->connection->prepare($queryById);
            $stmt->bindParam(":id", $id);
            $resul = array();
            if ($stmt->execute()) {
                while ($row = $stmt->fetchObject(__CLASS__)) {
                    $resul[] = $row;
                }
            }
            DbConnect::closeConnection($this->connection);

            return $resul;
        } catch (PDOException $e) {
            return ['errorMsg' => $e->getMessage()];
        }
        $this->connection = null;
    }
    public function getEventById(int $id)
    {
        try {
            $this->connection = DbConnect::getConnection();
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "SELECT evento_id, organizador_id, nome, background, estado_evento, data_inicio, data_fim, local, usuarios.email, usuarios.primeiro_nome, usuarios.sobrenome, usuarios.id FROM " . $this->tableName . " INNER JOIN usuarios ON usuarios.id=eventos.organizador_id WHERE evento_id=:id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $row = $stmt->fetchObject(__CLASS__);

            if (!$row) {
                DbConnect::closeConnection($this->connection);

                return false;
            }

            DbConnect::closeConnection($this->connection);
            return $row;
        } catch (PDOException $e) {

            throw new Exception($e->getMessage());

            // return false;
        }
    }
    public function insertEvent(array $formData, $file)
    {
        parent::isLoggedIn();
        extract($formData);
        extract($file);

        $this->nome = htmlspecialchars(strip_tags($nome));
        $this->estado_evento = htmlspecialchars(strip_tags($estado_evento));
        $this->cor = $cor;
        $this->background = str_replace(' ', '', $name);
        $this->data_inicio = htmlspecialchars(strip_tags($data_hora_inicio));
        $this->data_fim = htmlspecialchars(strip_tags($data_hora_fim));
        $this->local = htmlspecialchars(strip_tags($local));
        $this->descricao = htmlspecialchars(strip_tags($descricao));
        $this->organizador_id = htmlspecialchars(strip_tags($organizador));

        $this->connection = DbConnect::getConnection();

        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "INSERT INTO $this->tableName (nome,background,data_inicio,data_fim,cor,estado_evento, local,descricao,organizador_id, created_at) VALUES (:nome,:background,:data_inicio,:data_fim,:cor, :estado_evento,:local,:descricao,:organizador, :created_at)";

            $statement = $this->connection->prepare($query);
            $now = date('Y-m-d H:i:s');

            $statement->bindParam(':nome', $this->nome);
            $statement->bindParam(':estado_evento', $this->estado_evento);
            $statement->bindParam(':cor', $this->cor);
            $statement->bindParam(':background', $this->background);
            $statement->bindParam(':data_inicio', $this->data_inicio);
            $statement->bindParam(':data_fim', $this->data_fim);
            $statement->bindParam(':local', $this->local);
            $statement->bindParam(':descricao', $this->descricao);
            $statement->bindParam(':organizador', $this->organizador_id);
            $statement->bindParam(':created_at', $now);

            $statement->execute();

            if (isset($name) && !empty($name)) {
                $lastId = $this->connection->lastInsertId();
                // criando um directorio para as imagens
                $usrImagesDir = "./images/eventos/$lastId/";
                // director para imagem de cada usuario
                mkdir($usrImagesDir, 0755);
                move_uploaded_file($tmp_name, $usrImagesDir . $this->background);
                DbConnect::closeConnection($this->connection);
                return true;
            }
        } catch (PDOException $e) {
            DbConnect::closeConnection($this->connection);
            return false;
        }
    }
    public function update($formData, $file)
    {
        parent::isLoggedIn();
        extract($formData);
        extract($file);

        $actualPicture = $this->getEventById((int) $id)->background;

        $newFileName = empty($name) ? str_replace(' ', '', $actualPicture) : $name;

        $this->nome = htmlspecialchars(strip_tags($nome));
        $this->evento_id = htmlspecialchars(strip_tags($id));
        $this->background = $newFileName;
        $this->data_inicio = htmlspecialchars(strip_tags($data_hora_inicio));
        $this->data_fim = htmlspecialchars(strip_tags($data_hora_fim));
        $this->local = htmlspecialchars(strip_tags($local));
        $this->descricao = htmlspecialchars(strip_tags($descricao));
        $this->organizador_id = htmlspecialchars(strip_tags($organizador));

        $this->connection = DbConnect::getConnection();
        try {

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $updateQuery = "UPDATE " . $this->tableName . " SET nome = :nome, background = :background, data_inicio = :data_inicio, data_fim = :data_fim, local = :local, descricao = :descricao, organizador_id = :organizador, updated_at = :updated_at WHERE evento_id=:id";

            $statement = $this->connection->prepare($updateQuery);
            $now = date('Y-m-d H:i:s');

            $statement->bindParam(':nome', $this->nome);
            $statement->bindParam(':background', $this->background);
            $statement->bindParam(':data_inicio', $this->data_inicio);
            $statement->bindParam(':data_fim', $this->data_fim);
            $statement->bindParam(':local', $this->local);
            $statement->bindParam(':descricao', $this->descricao);
            $statement->bindParam(':organizador', $this->organizador_id);
            $statement->bindParam(':updated_at', $now);
            $statement->bindParam(":id", $this->evento_id);

            $statement->execute();

            $lastId = $this->evento_id;
            // criando um directorio para as imagens
            $usrImagesDir = "./images/eventos/$lastId/";

            // director para imagem de cada usuario
            if (isset($name) && !empty($name)) {
                if (file_exists($usrImagesDir)) {
                    parent::rrmdir($usrImagesDir);
                }
                mkdir($usrImagesDir, 0755);
                move_uploaded_file($tmp_name, $usrImagesDir . $this->background);
            }

            DbConnect::closeConnection($this->connection);
            return true;

        } catch (PDOException $e) {
            return false;
        }
    }
    public function concluirEvento($id)
    {
        parent::isLoggedIn();

        $this->connection = DbConnect::getConnection();
        try {

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $updateQuery = "UPDATE " . $this->tableName . " SET estado_evento = 'concluido' WHERE evento_id = :id";

            $statement = $this->connection->prepare($updateQuery);
            $statement->bindParam(':id', $id);

            $statement->execute();

            DbConnect::closeConnection($this->connection);
            return true;

        } catch (PDOException $e) {
            DbConnect::closeConnection($this->connection);
            return false;
        }
    }
    public function deleteEvent($id)
    {
        parent::isLoggedIn();
        $this->connection = DbConnect::getConnection();
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $inscricoes = new Inscricao();
        $hasInscricoes = $inscricoes->getInscricaoId($id);

        if ($hasInscricoes) {
            $inscricoes->delete($id);
        }

        $query = "DELETE FROM " . $this->tableName . " WHERE evento_id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $this->connection = null;
            return true;
        }

        $this->connection = null;
        return false;
    }
}
