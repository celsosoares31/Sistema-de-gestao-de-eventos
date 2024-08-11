<?php
declare (strict_types = 1);

namespace App\class\entity;

use App\class\config\DbConnect;
use Exception;
use PDO;
use PDOException;

final class Inscricao
{
    private $connection;
    private $tableName = 'inscricoes';

    public $profile_pic;
    public $data_hora_inscricao;
    public int $participante_id;
    public int $evento_id;
    public $data_inicio;
    public $hora_inicio;
    public $data_final;
    public $hora_final;
    public $total;
    public $nome_organizador;

    public function inscricao($participante_id, $evento_id)
    {

        $this->participante_id = (int) $participante_id;
        $this->evento_id = (int) $evento_id;
        $this->data_hora_inscricao = date('Y-m-d H:i:s');

        $this->connection = DbConnect::getConnection();
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "INSERT INTO " . $this->tableName . " (evento_id, participante_id, data_hora_inscricao ) VALUES (:evento_id,:participante_id,:data_hora_inscricao)";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(':evento_id', $this->evento_id);
            $statement->bindParam(':participante_id', $this->participante_id);
            $statement->bindParam(':data_hora_inscricao', $this->data_hora_inscricao);
            $statement->execute();

            if ($statement->rowCount() != 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

    }
    public function getInscricaoId($id)
    {
        $this->connection = DbConnect::getConnection();
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = 'select usuarios.profile_pic, usuarios.primeiro_nome, usuarios.sobrenome, inscricoes.participante_id, inscricoes.evento_id from eventos join ' . $this->tableName . ' on eventos.evento_id=inscricoes.evento_id join usuarios on usuarios.id=inscricoes.participante_id WHERE inscricoes.evento_id = :id';
            $stmt = $this->connection->prepare($query);
            $resul = array();
            $stmt->bindParam("id", $id);

            if ($stmt->execute()) {
                while ($row = $stmt->fetchObject(__CLASS__)) {
                    $resul[] = $row;
                }
            }

            DbConnect::closeConnection($this->connection);
            return $resul;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
            // return ['errorMsg' => $e->getMessage()];
        }
        $this->connection = null;
    }
    public function getInscricaoUserId($id)
    {
        $this->connection = DbConnect::getConnection();
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = 'SELECT inscricao_id, eventos.nome, eventos.local, eventos.evento_id, eventos.cor, usuarios.primeiro_nome, usuarios.sobrenome, eventos.data_inicio, eventos.data_fim from eventos join ' . $this->tableName . ' on eventos.evento_id=inscricoes.evento_id join usuarios on usuarios.id=eventos.organizador_id WHERE inscricoes.participante_id = :id AND (eventos.estado_evento = "activo" OR eventos.organizador_id = :orgId)';
            $stmt = $this->connection->prepare($query);
            $resul = array();
            $stmt->bindParam("id", $id);
            $stmt->bindParam(":orgId", $id);

            if ($stmt->execute()) {
                while ($row = $stmt->fetchObject(__CLASS__)) {
                    $resul[] = $row;
                }
            }

            DbConnect::closeConnection($this->connection);
            return $resul;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        $this->connection = null;
    }
    public function getTotalInscricoesPerEvent($id)
    {
        $this->connection = DbConnect::getConnection();
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $query = "SELECT inscricao_id, COUNT(inscricao_id) as total from eventos join inscricoes on eventos.evento_id=inscricoes.evento_id join usuarios on usuarios.id=eventos.organizador_id WHERE inscricoes.evento_id=:id";
            $query = "SELECT inscricao_id, COUNT(inscricao_id) as total
            FROM eventos
            JOIN inscricoes ON eventos.evento_id = inscricoes.evento_id
            JOIN usuarios ON usuarios.id = eventos.organizador_id
            WHERE inscricoes.evento_id = :id
            GROUP BY inscricao_id";
            $stmt = $this->connection->prepare($query);
            $resul = array();
            $stmt->bindParam("id", $id);

            if ($stmt->execute()) {
                while ($row = $stmt->fetchObject(__CLASS__)) {
                    $resul[] = $row;
                }
            }
            // var_dump($resul);
            // exit;
            DbConnect::closeConnection($this->connection);
            return $resul;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        $this->connection = null;
    }
    public function delete($id)
    {
        $this->connection = DbConnect::getConnection();
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "DELETE FROM " . $this->tableName . " WHERE evento_id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            DbConnect::closeConnection($this->connection);
            return true;
        } catch (PDOException $e) {
            DbConnect::closeConnection($this->connection);
            return false;
        }
        $this->connection = null;
    }
}
