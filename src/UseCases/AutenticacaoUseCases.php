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

    public function criarContaBancoDeDados(AutenticacaoGateway $autenticacaoGateway, $dados)
    {
        if (empty($dados["cpf"])) {
            throw new \Exception("O CPF é obrigatório.", 400);
        }

        if (empty($dados["nome"])) {
            throw new \Exception("O nome é obrigatório.", 400);
        }

        if (empty($dados["email"])) {
            throw new \Exception("O email é obrigatório.", 400);
        }

        if (empty($dados["endereco"])) {
            throw new \Exception("O endereço é obrigatório.", 400);
        }

        if (empty($dados["telefone"])) {
            throw new \Exception("O telefone é obrigatório.", 400);
        }

        $criarContaBancoDeDados = $autenticacaoGateway->criarContaBancoDeDados($dados);

        if (!$criarContaBancoDeDados) {
            throw new \Exception("Ocorreu um erro ao salvar registro no banco de dados.", 500);
        }
    }

    public function excluirContaBancoDeDados(AutenticacaoGateway $autenticacaoGateway, $cpf)
    {
        if (empty($cpf)) {
            throw new \Exception("O CPF é obrigatório.", 400);
        }

        $excluirContaBancoDeDados = $autenticacaoGateway->excluirContaBancoDeDados($cpf);

        if (!$excluirContaBancoDeDados) {
            throw new \Exception("Ocorreu um erro ao excluir registro no banco de dados.", 500);
        }
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
        $resultadoArray = json_decode($resultado, true);

        if (!empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-criado-com-sucesso") {
            return $resultadoArray;
        } else {
            throw new \Exception("Ocorreu um erro ao criar conta no Cognito.", 400);
        }
    }

    public function excluirContaCognito($cpf)
    {
        if (empty($cpf)) {
            throw new \Exception("O CPF é obrigatório.", 400);
        }

        $resultado = $this->autenticacaoGateway->excluirContaCognito($cpf);
        $resultadoArray = json_decode($resultado, true);

        if (!empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-excluido-com-sucesso") {
            return true;
        } else {
            throw new \Exception("Ocorreu um erro ao excluir conta. Detalhes: " . $resultadoArray["status"]);
        }
    }

    public function excluirContaCognitoSemRetorno($cpf)
    {
        $this->autenticacaoGateway->excluirContaCognito($cpf);
    }
}
