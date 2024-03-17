<?php

namespace Autenticacao\Controllers;

require "./src/Interfaces/Controllers/AutenticacaoControllerInterface.php";
require "./src/UseCases/AutenticacaoUseCases.php";

use Autenticacao\Gateways\AutenticacaoGateway;
use Autenticacao\Interfaces\Controllers\AutenticacaoControllerInterface;
use Autenticacao\UseCases\AutenticacaoUseCases;

class AutenticacaoController implements AutenticacaoControllerInterface
{
    private $autenticacaoUseCases;
    public function __construct()
    {
        $this->autenticacaoUseCases = new AutenticacaoUseCases();
    }
    function gerarToken($cpf)
    {
        $token = $this->autenticacaoUseCases->gerarToken($cpf);
        return $token;
    }

    function criarContaCognito($cpf, $nome, $email)
    {
        $token = $this->autenticacaoUseCases->criarContaCognito($cpf, $nome, $email);
        return $token;
    }

    function excluirContaCognito($cpf)
    {
        $token = $this->autenticacaoUseCases->excluirContaCognito($cpf);
        return $token;
    }

    function excluirContaCognitoSemRetorno($cpf)
    {
        $this->autenticacaoUseCases->excluirContaCognitoSemRetorno($cpf);
    }

    function criarContaBancoDeDados($dbConnection, $dados)
    {
        $autenticacaoGateway = new AutenticacaoGateway($dbConnection);
        $autenticacaoUseCases = new AutenticacaoUseCases();
        $resultado = $autenticacaoUseCases->criarContaBancoDeDados($autenticacaoGateway, $dados);
        return $resultado;
    }

    function excluirContaBancoDeDados($dbConnection, $cpf)
    {
        $autenticacaoGateway = new AutenticacaoGateway($dbConnection);
        $autenticacaoUseCases = new AutenticacaoUseCases();
        $resultado = $autenticacaoUseCases->excluirContaBancoDeDados($autenticacaoGateway, $cpf);
        return $resultado;
    }
}
