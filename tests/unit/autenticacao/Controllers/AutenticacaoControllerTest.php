<?php

require "./src/Controllers/AutenticacaoController.php";

use PHPUnit\Framework\TestCase;
use Autenticacao\Controllers\AutenticacaoController;

class AutenticacaoControllerTest extends TestCase
{
    private $autenticacaoController;
    private $cpf;
    protected function setUp(): void
    {
        $this->autenticacaoController = new AutenticacaoController();
        $this->cpf = "80621711080";
    }
    public function testGerarTokenComSucesso()
    {
        $this->autenticacaoController->excluirContaCognitoSemRetorno($this->cpf);
        $resultado = $this->autenticacaoController->criarContaCognito($this->cpf, 'Carmo', 'usuario_teste@gmail.com');
        $usuarioCadastradoComSucesso = !empty($resultado["status"]) && $resultado["status"] == "usuario-criado-com-sucesso";
        $this->assertTrue($usuarioCadastradoComSucesso);
        $resultado = $this->autenticacaoController->gerarToken($this->cpf);
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
        $this->autenticacaoController->excluirContaCognitoSemRetorno($this->cpf);
        $resultado = $this->autenticacaoController->criarContaCognito($this->cpf, 'Carmo', 'usuario_teste@gmail.com');
        $usuarioCadastradoComSucesso =!empty($resultado["status"]) && $resultado["status"] == "usuario-criado-com-sucesso";
        $this->assertTrue($usuarioCadastradoComSucesso);
    }
}
