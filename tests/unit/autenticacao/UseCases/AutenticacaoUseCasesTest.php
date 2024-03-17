<?php

use PHPUnit\Framework\TestCase;
use Autenticacao\UseCases\AutenticacaoUseCases;

class AutenticacaoUseCasesTest extends TestCase
{
    private $cpf;
    private $autenticacaoUseCases;
    protected function setUp(): void
    {
        $this->autenticacaoUseCases = new AutenticacaoUseCases();
        $this->cpf = "59162298011";
    }
    public function testGerarTokenComSucesso()
    {
        $this->autenticacaoUseCases->excluirContaCognitoSemRetorno($this->cpf);
        $resultado = $this->autenticacaoUseCases->criarContaCognito($this->cpf, 'Carmo', 'usuario_teste@gmail.com');
        $usuarioCadastradoComSucesso = !empty($resultado["status"]) && $resultado["status"] == "usuario-criado-com-sucesso";
        $this->assertTrue($usuarioCadastradoComSucesso);
        $resultado = $this->autenticacaoUseCases->gerarToken($this->cpf);
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
        $this->autenticacaoUseCases->excluirContaCognitoSemRetorno($this->cpf);
        $resultado = $this->autenticacaoUseCases->criarContaCognito($this->cpf, 'Carmo', 'usuario_teste@gmail.com');
        $usuarioCadastradoComSucesso = !empty($resultado["status"]) && $resultado["status"] == "usuario-criado-com-sucesso";
        $this->assertTrue($usuarioCadastradoComSucesso);
    }
    public function testCriarContaCognitoComCPFNaoInformado()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito('', 'Carmo', 'usuario_teste@gmail.com');
        } catch (Exception $e) {
            $this->assertEquals("O CPF é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComNomeNaoInformado()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito($this->cpf, '', 'usuario_teste@gmail.com');
        } catch (Exception $e) {
            $this->assertEquals("O nome é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComEmailNaoInformado()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito($this->cpf, 'Carmo', '');
        } catch (Exception $e) {
            $this->assertEquals("O email é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComCPFInvalido()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito('1111', 'Carmo', 'usuario_teste@gmail.com');
        } catch (Exception $e) {
            $this->assertEquals("O CPF informado é inválido.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCriarContaCognitoComEmailInvalido()
    {
        try {
            $this->autenticacaoUseCases->criarContaCognito($this->cpf, 'Carmo', 'rodrigocarmodevgmail.com');
        } catch (Exception $e) {
            $this->assertEquals("O email informado é inválido.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
}
