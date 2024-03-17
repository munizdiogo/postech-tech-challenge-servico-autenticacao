<?php

namespace Autenticacao\Gateways;

require "./src/Interfaces/Gateways/AutenticacaoGatewayInterface.php";

use Autenticacao\Interfaces\DbConnection\DbConnectionInterface;
use Autenticacao\Interfaces\Gateways\AutenticacaoGatewayInterface;

class AutenticacaoGateway implements AutenticacaoGatewayInterface
{
    private $repositorioDados;

    public function __construct(DbConnectionInterface $database = null)
    {
        $this->repositorioDados = $database;
    }

    private $urlAws = "https://iut6byhlui.execute-api.us-east-1.amazonaws.com";

    public function gerarToken($cpf)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->urlAws}/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>
            '{
                "cpf": "' . str_replace([".", "-"], "", $cpf) . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        http_response_code(200);
        return $response;
    }

    public function criarContaCognito($cpf, $nome, $email)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->urlAws}/criar-usuario",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                        "email": "' . $email . '",
                        "name": "' . $nome . '",
                        "cpf": "' . $cpf . '"
                    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        http_response_code(200);
        return $response;
    }

    public function excluirContaCognito($cpf)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->urlAws}/excluir-conta-cognito",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                        "cpf": "' . $cpf . '"
                    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        http_response_code(200);
        return $response;
    }

    public function criarContaBancoDeDados($dados)
    {
        $cpf = str_replace([".", "-"], "", $dados["cpf"]);
        $clienteJaCadastrado = $this->repositorioDados->buscarPorCpf("clientes", $cpf);

        if (!empty($clienteJaCadastrado)) {
            throw new \Exception("Cliente já cadastrado.", 400);
        }

        $dadosParaCriarConta = [
            "data_criacao" => date('Y-m-d h:i:s'),
            "cpf" => $cpf,
            "nome" => $dados["nome"],
            "email" => $dados["email"],
            "endereco" => $dados["endereco"],
            "telefone" => $dados["telefone"]
        ];

        $idCliente = $this->repositorioDados->inserir("clientes", $dadosParaCriarConta);

        if (empty($idCliente)) {
            return false;
        }

        return true;
    }

    public function excluirContaBancoDeDados($cpf)
    {
        $cpf = str_replace([".", "-"], "", $cpf);
        $clienteCadastrado = $this->repositorioDados->buscarPorCpf("clientes", $cpf);

        if (empty($clienteCadastrado)) {
            throw new \Exception("Cliente não encontrado.", 400);
        }

        $camposEValores = [
            "cpfCliente" => $cpf,
            "nome" => NULL,
            "email" => NULL,
            "cpf" => NULL,
            "endereco" => NULL,
            "telefone" => NULL
        ];

        $exclusaoTabelaClientes = $this->repositorioDados->excluir("clientes", $camposEValores);

        if (!$exclusaoTabelaClientes) {
            return false;
        }

        $camposEValores = [
            "cpf" => NULL,
            "cpfCliente" => $cpf,
        ];

        $exclusaoTabelaPedidos = $this->repositorioDados->excluir("pedidos", $camposEValores);

        if (!$exclusaoTabelaPedidos) {
            return false;
        }

        return true;
    }
}
