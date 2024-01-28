<?php

namespace Autenticacao\Interfaces\UseCases;

interface AutenticacaoUseCasesInterface
{
    public function gerarToken(string $cpf);
    public function criarContaCognito(string $cpf, string $nome, string $email);
}
