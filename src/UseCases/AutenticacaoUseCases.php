<?php

namespace Autenticacao\UseCases;

require "./src/Interfaces/UseCases/AutenticacaoUseCasesInterface.php";
require "./src/Gateways/AutenticacaoGateway.php";
require "./utils/ValidarCPF.php";
require "./utils/ValidarEmail.php";

use Autenticacao\Gateways\AutenticacaoGateway;
use Autenticacao\Interfaces\UseCases\AutenticacaoUseCasesInterface;

class AutenticacaoUseCases implements AutenticacaoUseCasesInterface
{
    private $autenticacaoGateway;
    public function __construct()
    {
        $this->autenticacaoGateway = new AutenticacaoGateway();
    }
    public function gerarToken($cpf)
    {
        if (empty($cpf)) {
            throw new \Exception("O CPF é obrigatório.", 400);
        }

        $cpfValido = validarCPF($cpf);

        if (!$cpfValido) {
            throw new \Exception("O CPF informado é inválido.", 400);
        }

        $resultado = $this->autenticacaoGateway->gerarToken($cpf);
        return $resultado;
    }
    public function criarContaCognito($cpf, $nome, $email)
    {
        if (empty($cpf)) {
            throw new \Exception("O CPF é obrigatório.", 400);
        }

        if (empty($nome)) {
            throw new \Exception("O nome é obrigatório.", 400);
        }

        if (empty($email)) {
            throw new \Exception("O email é obrigatório.", 400);
        }

        $cpfValido = validarCPF($cpf);

        if (!$cpfValido) {
            throw new \Exception("O CPF informado é inválido.", 400);
        }

        $emailValido = validarEmail($email);

        if (!$emailValido) {
            throw new \Exception("O email informado é inválido.", 400);
        }

        $resultado = $this->autenticacaoGateway->criarContaCognito($cpf, $nome, $email);
        return $resultado;
    }
}
