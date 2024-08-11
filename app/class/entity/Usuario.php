<?php
declare(strict_types=1);
namespace App\class\entity;

use App\class\config\DbConnect;
use App\class\controller\Controller;
use Exception;
use PDO;
use PDOException;

final class Usuario
{
    private $connection;
    public $id;
    public $nome_usuario;
    public $email;
    public $senha;
    public $profile_pic;
    public $created_at;
    public $primeiro_nome;
    public $sobrenome;

    public function __construct()
    {
        $this->connection = DbConnect::getConnection();
    }
    public function getAllUsers()
    {
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query_allUsers = 'SELECT * FROM usuarios';
            $stmt = $this->connection->prepare($query_allUsers);
            if($stmt->execute()) {
                while ($row = $stmt->fetchObject(__CLASS__)) {
                    $resul[] = $row;
                }
            }
            DbConnect::closeConnection($this->connection);
            return $resul;
        } catch(PDOException $e) {
            return ['errorMsg' => $e->getMessage()];
        }
        $this->connection = null;
    }
    public function getUserByEmail(string $email): array
    {
        if($this->connection != PDO::ERRMODE_EXCEPTION) {
            try {
                $newEmail = strtolower($email);
                $this->connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $this->connection->prepare("SELECT id, primeiro_nome, sobrenome, email, senha, profile_pic FROM usuarios WHERE email = :email");
                $stmt -> bindParam(":email", $newEmail);
                $stmt->execute();
                $result = $stmt->fetch();
                if(!$result) {
                    return ['errorMsg' => "Email ou senha invalido"];
                }
                return $result;
            } catch(PDOException $e) {
                return ['errorMsg' => $e->getMessage()];
            }
            $this->connection = null;
        } else {
            return ['errorMsg' => "Erro de conexao ao banco de dados"];
        }
    }
    public function getUserById(int $id)
    {
        try {
            $this->connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->connection->prepare("SELECT * FROM usuarios WHERE id=:id");
            $stmt -> bindParam(":id", $id);
            $stmt->execute();

            $result = $stmt->fetchObject(__CLASS__);
            return $result;
        } catch(PDOException $e) {
            return false;
        }
    }
    public function insertUser(array $formData, array $file)
    {
        extract($formData);
        extract($file);
        $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);
     
        $newFileName = str_replace(' ', '', $name);
        $newEmail = strtolower($email);

        $data = [
            ':primeiro_nome' => $primeiro_nome,
            ':sobrenome' => $sobrenome,
            ':email' => $newEmail,
            ':senha' => $hashedPassword,
            ':profile_pic' => $newFileName,
            ':created_at' => date('Y-m-d H:i:s'),
        ];
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "INSERT INTO usuarios (email, primeiro_nome, sobrenome, senha,profile_pic, created_at) VALUES (:email, :primeiro_nome, :sobrenome, :senha,:profile_pic,:created_at)";
            $query1 = "SELECT email from usuarios where email = :email";
            $statement1 = $this->connection->prepare($query1);
            $statement1->bindParam(':email', $newEmail);
            $statement1->execute();
            if($statement1->rowCount() != 0) {
                printError('O email ja existe no banco de dados', 'danger');
                header('refresh:1.5; ');
            } else {
                $statement = $this->connection->prepare($query);
                $statement->execute($data);

                if (isset($newFileName) && !empty($newFileName)) {
                    $lastId = $this->connection->lastInsertId();
                    // criando um directorio para as imagens
                    $usrImagesDir = "./app/images/usuarios/$lastId/";
                    // director para imagem de cada usuario
                    mkdir($usrImagesDir, 0755);
                    $fileName = $newFileName;
                    move_uploaded_file($tmp_name, $usrImagesDir.$fileName);
                   return true;
                }else{
                    return false;
                }
            }
        } catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}