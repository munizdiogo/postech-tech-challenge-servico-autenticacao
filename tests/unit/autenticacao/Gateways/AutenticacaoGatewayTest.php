<?php

use PHPUnit\Framework\TestCase;
use Autenticacao\Gateways\AutenticacaoGateway;

class AutenticacaoGatewayTest extends TestCase
{
    private $autenticacaoGateway;
    protected function setUp(): void
    {
        $this->autenticacaoGateway = new AutenticacaoGateway();
    }
    public function testGerarTokenComSucesso()
    {
        $resultado = $this->autenticacaoGateway->gerarToken('42157363823');
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
        $resultado = $this->autenticacaoGateway->criarContaCognito('42157363823', 'Carmo', 'rodrigocarmodev@gmail.com');
        $resultadoArray = json_decode($resultado);
        $usuarioCadastradoComSucesso = strpos($resultado, "User account already exists") || (!empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-criado-com-sucesso");
        $this->assertTrue($usuarioCadastradoComSucesso);
    }
    public function testCriarContaCognitoComErro()
    {
        $resultado = $this->autenticacaoGateway->criarContaCognito('42157363823', 'Carmo', 'rodrigocarmodev@gmail.com');
        $resultadoArray = json_decode($resultado);
        $usuarioCadastradoComSucesso = !empty($resultadoArray["status"]) && $resultadoArray["status"] != "usuario-criado-com-sucesso";
        $this->assertFalse($usuarioCadastradoComSucesso);
    }
}
