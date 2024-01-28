<?php

header('Content-Type: application/json; charset=utf-8');

require "./utils/RespostasJson.php";
require "./src/Controllers/AutenticacaoController.php";

use Autenticacao\Controllers\AutenticacaoController;

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
                $cpf = $_POST['cpf'] ?? '';
                $nome = $_POST['nome'] ?? '';
                $email = $_POST['email'] ?? '';
                $resultado = $autenticacaoController->criarContaCognito($cpf, $nome, $email);
                echo $resultado;
            } catch (\Exception $e) {
                retornarRespostaJSON($e->getMessage(), $e->getCode());
            }
            break;

        default:
            echo '{"mensagem": "A ação informada é inválida."}';
            http_response_code(400);
    }
}
