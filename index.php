<?php

header('Content-Type: application/json; charset=utf-8');

require "./utils/RespostasJson.php";
require "./src/External/MySqlConnection.php";
require "./src/Controllers/AutenticacaoController.php";

use Autenticacao\External\MySqlConnection;
use Autenticacao\Controllers\AutenticacaoController;

$dbConnection = new MySqlConnection();
$autenticacaoController = new AutenticacaoController();

if (!empty($_GET["acao"])) {
    switch ($_GET["acao"]) {
        case 'gerarToken':
            try {
                $token = $autenticacaoController->gerarToken($_POST["cpf"]);
                echo '{"token":"' . $token . '"}';
            } catch (\Exception $e) {
                retornarRespostaJSON($e->getMessage(), $e->getCode());
            }
            break;
        case 'criarConta':
            try {
                $dados = [
                    "cpf" => $_POST['cpf'] ?? '',
                    "nome" => $_POST['nome'] ?? '',
                    "email" =>  $_POST['email'] ?? '',
                    "telefone" => $_POST['telefone'] ?? '',
                    "endereco" =>  $_POST['endereco'] ?? ''
                ];
                $autenticacaoController->criarContaBancoDeDados($dbConnection, $dados);
                $autenticacaoController->criarContaCognito($dados["cpf"], $dados["nome"], $dados["email"]);
                retornarRespostaJSON("Conta criada com sucesso!", 201);
            } catch (\Exception $e) {
                retornarRespostaJSON($e->getMessage(), $e->getCode());
            }
            break;

        case 'excluirConta':
            try {
                $cpf = $_POST['cpf'] ?? '';
                $autenticacaoController->excluirContaCognito($cpf);
                $autenticacaoController->excluirContaBancoDeDados($dbConnection, $cpf);
                retornarRespostaJSON("Conta excluída com sucesso!", 200);
            } catch (\Exception $e) {
                retornarRespostaJSON($e->getMessage(), $e->getCode());
            }
            break;

        default:
            echo '{"mensagem": "A ação informada é inválida."}';
            http_response_code(400);
    }
}
