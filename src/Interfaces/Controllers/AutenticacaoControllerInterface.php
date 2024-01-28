<?php

namespace Autenticacao\Interfaces\Controllers;

interface AutenticacaoControllerInterface
{
    public function gerarToken($cpf);
    public function criarContaCognito($cpf, $nome, $email);
}
