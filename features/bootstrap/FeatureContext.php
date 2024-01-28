<?php

require "./src/Controllers/AutenticacaoController.php";

use Autenticacao\Controllers\AutenticacaoController;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\TestCase;

/**
 * Features context.
 */
class FeatureContext extends TestCase implements Context
{
    private $resultado;
    private $cpf;
    private $nome;
    private $email;
    private $exceptionMessage;
    private $exceptionCode;

    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */

    private $autenticacaoController;
    public function __construct()
    {
        $this->autenticacaoController = new AutenticacaoController();
    }

    /**
     * @Given que eu informe um CPF valido
     */
    public function queEuInformeUmCpfValido()
    {
        $this->cpf = "42157363823";
    }

    /**
     * @When eu executar a função gerarToken com um CPF válido
     */
    public function euExecutarAFuncaoGerartokenComUmCpfValido()
    {
        $this->resultado = $this->autenticacaoController->gerarToken('42157363823');
    }

    /**
     * @Then eu devo receber um token válido como resposta
     */
    public function euDevoReceberUmTokenValidoComoResposta()
    {
        $this->assertIsString($this->resultado);
        $this->assertNotEmpty($this->resultado);
    }

    /**
     * @Given que eu não forneça um CPF
     */
    public function queEuNaoFornecaUmCpf()
    {
        $this->cpf = '';
    }

    /**
     * @When eu executar a função gerarToken sem fornecer um CPF
     */
    public function euExecutarAFuncaoGerartokenSemFornecerUmCpf()
    {
        try {
            $this->autenticacaoController->gerarToken($this->cpf);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Given que eu forneça um CPF, nome e e-mail válidos
     */
    public function queEuFornecaUmCpfNomeEEMailValidos()
    {
        $this->cpf = "42157363823";
        $this->nome = "Rodrigo Carmo";
        $this->email = "rodrigocarmodev@gmail.com";
    }

    /**
     * @When eu executar a função criarContaCognito com um CPF, nome e e-mail válidos
     */
    public function euExecutarAFuncaoCriarcontacognitoComUmCpfNomeEEMailValidos()
    {
        $this->resultado = $this->autenticacaoController->criarContaCognito($this->cpf,  $this->nome, $this->email);
    }

    /**
     * @Then eu devo receber uma resposta de sucesso do Cognito
     */
    public function euDevoReceberUmaRespostaDeSucessoDoCognito()
    {
        $resultadoArray = json_decode($this->resultado);
        $usuarioCadastradoComSucesso = strpos($this->resultado, "User account already exists") !== false || (!empty($resultadoArray["status"]) && $resultadoArray["status"] == "usuario-criado-com-sucesso");
        $this->assertTrue($usuarioCadastradoComSucesso);
    }

    /**
     * @When eu executar a função criarContaCognito sem fornecer um CPF
     */
    public function euExecutarAFuncaoCriarcontacognitoSemFornecerUmCpf()
    {
        $this->nome = "Rodrigo Carmo";
        $this->email = "rodrigocarmodev@gmail.com";
        try {
            $this->resultado = $this->autenticacaoController->criarContaCognito($this->cpf,  $this->nome, $this->email);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Given que eu forneça um e-mail inválido
     */
    public function queEuFornecaUmEMailInvalido()
    {
        $this->cpf = "42157363823";
        $this->nome = "Rodrigo Carmo";
        $this->email = "3333";
    }

    /**
     * @When eu executar a função criarContaCognito com um e-mail inválido
     */
    public function euExecutarAFuncaoCriarcontacognitoComUmEMailInvalido()
    {
        try {
            $this->resultado = $this->autenticacaoController->criarContaCognito($this->cpf,  $this->nome, $this->email);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then eu devo receber uma mensagem de erro indicando que o e-mail é inválido
     */
    public function euDevoReceberUmaMensagemDeErroIndicandoQueOEMailEInvalido()
    {
        $this->assertEquals("O email informado é inválido.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @Given que eu forneça um CPF inválido
     */
    public function queEuFornecaUmCpfInvalido()
    {
        $this->cpf = "asdasdsad";
        $this->nome = "Rodrigo Carmo";
        $this->email = "3333";
    }

    /**
     * @When eu executar a função gerarToken com um CPF inválido
     */
    public function euExecutarAFuncaoGerartokenComUmCpfInvalido()
    {
        try {
            $this->resultado = $this->autenticacaoController->gerarToken($this->cpf);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then eu devo receber uma mensagem de erro indicando que o CPF é inválido
     */
    public function euDevoReceberUmaMensagemDeErroIndicandoQueOCpfEInvalido()
    {
        $this->assertEquals("O CPF informado é inválido.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @Given que eu forneça um CPF já existente
     */
    public function queEuFornecaUmCpfJaExistente()
    {
        $this->cpf = "42157363823";
        $this->nome = "Rodrigo Carmo";
        $this->email = "rodrigocarmodev@gmail.com";
    }

    /**
     * @When eu executar a função criarContaCognito com um CPF que já possui uma conta no Cognito
     */
    public function euExecutarAFuncaoCriarcontacognitoComUmCpfQueJaPossuiUmaContaNoCognito()
    {
        $this->resultado = $this->autenticacaoController->criarContaCognito($this->cpf,  $this->nome, $this->email);
    }

    /**
     * @Then eu devo receber uma mensagem indicando que já existe uma conta com esse CPF
     */
    public function euDevoReceberUmaMensagemIndicandoQueJaExisteUmaContaComEsseCpf()
    {
        $this->assertTrue(strpos($this->resultado, "User account already exists") !== false);
    }

    /**
     * @Then eu devo receber uma mensagem de erro indicando que o CPF é obrigatório
     */
    public function euDevoReceberUmaMensagemDeErroIndicandoQueOCpfEObrigatorio()
    {
        $this->assertEquals("O CPF é obrigatório.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @When eu executar a função criarContaCognito sem fornecer nome
     */
    public function euExecutarAFuncaoCriarcontacognitoSemFornecerNome()
    {
        try {
            $this->resultado = $this->autenticacaoController->criarContaCognito($this->cpf,  $this->nome, $this->email);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then eu devo receber uma mensagem de erro indicando que o nome é obrigatório
     */
    public function euDevoReceberUmaMensagemDeErroIndicandoQueONomeEObrigatorio()
    {
        $this->assertEquals("O nome é obrigatório.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @Given que eu não forneça o e-mail
     */
    public function queEuNaoFornecaOEMail()
    {
        $this->cpf = "42157363823";
        $this->nome = "Rodrigo Carmo";
        $this->email = "";
    }

    /**
     * @When eu executar a função criarContaCognito sem fornecer e-mail
     */
    public function euExecutarAFuncaoCriarcontacognitoSemFornecerEMail()
    {
        try {
            $this->resultado = $this->autenticacaoController->criarContaCognito($this->cpf,  $this->nome, $this->email);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then eu devo receber uma mensagem de erro indicando que o e-mail é obrigatório
     */
    public function euDevoReceberUmaMensagemDeErroIndicandoQueOEMailEObrigatorio()
    {
        $this->assertEquals("O email é obrigatório.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @Given que eu não forneça o nome
     */
    public function queEuNaoFornecaONome()
    {
        $this->cpf = "42157363823";
        $this->nome = "";
        $this->email = "rodrigocarmodev@gmail.com";
    }
}
