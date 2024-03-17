<?php

use PHPUnit\Framework\TestCase;
use Autenticacao\Gateways\AutenticacaoGateway;

class AutenticacaoGatewayTest extends TestCase
{
    private $autenticacaoGateway;
    private $cpf;
    protected function setUp(): void
    {
        $this->cpf = "80621711080";
        $this->autenticacaoGateway = new AutenticacaoGateway();
    }
    public function testGerarTokenComSucesso()
    {
        $this->autenticacaoGateway->excluirContaCognito($this->cpf);
        $resultado = $this->autenticacaoGateway->criarContaCognito($this->cpf, 'Carmo', 'usuario_teste@gmail.com');
        $resultadoArray = json_decode($resultado, true);
        $usuarioCadastradoComSucesso = !empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-criado-com-sucesso";
        $this->assertTrue($usuarioCadastradoComSucesso);
        $resultado = $this->autenticacaoGateway->gerarToken($this->cpf);
        $this->assertIsString($resultado);
        $this->assertNotEmpty($resultado);
        $this->assertTrue(strpos($resultado, "Bearer ") !== false);
    }
    public function testGerarTokenComErro()
    {
        $resultado = $this->autenticacaoGateway->gerarToken('');
        $this->assertIsString($resultado);
        $this->assertNotEmpty($resultado);
        $this->assertTrue(strpos($resultado, "Bearer ") === false);
    }

    public function testCriarContaCognitoComSucesso()
    {
        $this->autenticacaoGateway->excluirContaCognito($this->cpf);
        $resultado = $this->autenticacaoGateway->criarContaCognito($this->cpf, 'Carmo', 'usuario_teste@gmail.com');
        $resultadoArray = json_decode($resultado, true);
        $usuarioCadastradoComSucesso = !empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-criado-com-sucesso";
        $this->assertTrue($usuarioCadastradoComSucesso);
    }
    public function testCriarContaCognitoComErro()
    {
        $resultado = $this->autenticacaoGateway->criarContaCognito($this->cpf, 'Carmo', 'usuario_teste@gmail.com');
        $usuarioCadastradoComSucesso = !empty($resultado["status"]) && $resultado["status"] != "usuario-criado-com-sucesso";
        $this->assertFalse($usuarioCadastradoComSucesso);
    }
}
