<?php

namespace Autenticacao\External;

require "./config.php";
require "./src/Interfaces/DbConnection/DbConnectionInterface.php";

use Autenticacao\Interfaces\DbConnection\DbConnectionInterface;
use \PDO;
use \PDOException;

class MySqlConnection implements DbConnectionInterface
{
    public function conectar()
    {
        $conn = null;

        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro na conexão com o banco de dados: " . $e->getMessage();
        }

        return $conn;
    }

    public function inserir(string $nomeTabela, array $parametros)
    {
        $db = $this->conectar();
        $nomesCampos = implode(", ", array_keys($parametros));
        $nomesValores = ":" . implode(", :", array_keys($parametros));
        $query = "INSERT INTO $nomeTabela ($nomesCampos) VALUES ($nomesValores)";
        $stmt = $db->prepare($query);

        foreach ($parametros as $chave => $valor) {
            $stmt->bindValue(":$chave", $valor);
        }

        try {
            return  $stmt->execute() ? $db->lastInsertId() : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function buscarPorCpf(string $nomeTabela, $cpf): array
    {
        $db = $this->conectar();
        $query = "SELECT *
                  FROM $nomeTabela
                  WHERE cpf = :cpf";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        $stmt->execute();
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return !empty($dados) ? $dados : [];
    }
}
