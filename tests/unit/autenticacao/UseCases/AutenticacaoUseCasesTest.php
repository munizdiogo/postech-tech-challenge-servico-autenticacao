<?php

use PHPUnit\Framework\TestCase;
use Autenticacao\UseCases\AutenticacaoUseCases;

class AutenticacaoUseCasesTest extends TestCase
{
    private $autenticacaoUseCases;
    protected function setUp(): void
    {
        $this->autenticacaoUseCases = new AutenticacaoUseCases();
    }
    public function testGerarTokenComSucesso()
    {
        $resultado = $this->autenticacaoUseCases->gerarToken('42157363823');
        $this->assertIsString($resultado);
        $this->assertNotEmpty($resultado);
    }
    public function testGerarTokenComCPFNaoInformado()
    {
        try {
            $this->autenticacaoUseCases->gerarToken('');
        } catch (Exception $e) {
            $this->assertEquals("O CPF é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testGerarTokenComCPFInvalido()
    {
        try {
            $this->autenticacaoUseCases->gerarToken('1111');
        } catch (Exception $e) {
            $this->assertEquals("O CPF informado é inválido.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testCriarContaCognitoComSucesso()
    {
        $resultado = $this->autenticacaoUseCases->criarContaCognito('42157363823', 'Carmo', 'rodrigocarmodev@gmail.com');
        $resultadoArray = json_decode($resultado);
        $usuarioCadastradoComSucesso = strpos($resultado, "User account already exists") || (!empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-criado-com-sucesso");
        $this->assertTrue($usuarioCadastradoComSucesso);
    }
    public function testCriarContaCognitoComCPFNaoInformado()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito('', 'Carmo', 'rodrigocarmodev@gmail.com');
        } catch (Exception $e) {
            $this->assertEquals("O CPF é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComNomeNaoInformado()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito('42157363823', '', 'rodrigocarmodev@gmail.com');
        } catch (Exception $e) {
            $this->assertEquals("O nome é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComEmailNaoInformado()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito('42157363823', 'Carmo', '');
        } catch (Exception $e) {
            $this->assertEquals("O email é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComCPFInvalido()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito('1111', 'Carmo', 'rodrigocarmodev@gmail.com');
        } catch (Exception $e) {
            $this->assertEquals("O CPF informado é inválido.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComEmailInvalido()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito('42157363823', 'Carmo', 'rodrigocarmodevgmail.com');
        } catch (Exception $e) {
            $this->assertEquals("O email informado é inválido.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
}
