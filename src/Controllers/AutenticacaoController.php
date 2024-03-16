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

    function criarContaBancoDeDados($dbConnection, $cpf, $nome, $email)
    {
        $autenticacaoGateway = new AutenticacaoGateway($dbConnection);
        $autenticacaoUseCases = new AutenticacaoUseCases();
        $resultado = $autenticacaoUseCases->criarContaBancoDeDados($autenticacaoGateway, $cpf, $nome, $email);
        return $resultado;
    }
}
