<?php

require "./src/Controllers/AutenticacaoController.php";

use PHPUnit\Framework\TestCase;
use Autenticacao\Controllers\AutenticacaoController;

class AutenticacaoControllerTest extends TestCase
{
    private $autenticacaoController;
    protected function setUp(): void
    {
        $this->autenticacaoController = new AutenticacaoController();
    }
    public function testGerarTokenComSucesso()
    {
        $resultado = $this->autenticacaoController->gerarToken('42157363823');
        $this->assertIsString($resultado);
        $this->assertNotEmpty($resultado);
    }
    public function testGerarTokenComCPFNaoInformado()
    {
        try {
            $this->autenticacaoController->gerarToken('');
        } catch (Exception $e) {
            $this->assertEquals("O CPF é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testCriarContaCognitoComSucesso()
    {
        $resultado = $this->autenticacaoController->criarContaCognito('42157363823', 'Carmo', 'rodrigocarmodev@gmail.com');
        $resultadoArray = json_decode($resultado);
        $usuarioCadastradoComSucesso = strpos($resultado, "User account already exists") || (!empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-criado-com-sucesso");
        $this->assertTrue($usuarioCadastradoComSucesso);
    }
}
