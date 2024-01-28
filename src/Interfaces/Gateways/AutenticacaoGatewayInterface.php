<?php

namespace Autenticacao\Interfaces\Gateways;


interface AutenticacaoGatewayInterface
{
    public function gerarToken(string $cpf);
    public function criarContaCognito(string $cpf, string $nome, string $email);
}
