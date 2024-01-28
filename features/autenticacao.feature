Feature: Controle de autenticação

    Scenario: Gerar Token com CPF válido
        Given que eu informe um CPF valido
        When eu executar a função gerarToken com um CPF válido
        Then eu devo receber um token válido como resposta

    Scenario: Gerar Token sem fornecer CPF
        Given que eu não forneça um CPF
        When eu executar a função gerarToken sem fornecer um CPF
        Then eu devo receber uma mensagem de erro indicando que o CPF é obrigatório

    Scenario: Criar Conta Cognito com dados válidos
        Given que eu forneça um CPF, nome e e-mail válidos
        When eu executar a função criarContaCognito com um CPF, nome e e-mail válidos
        Then eu devo receber uma resposta de sucesso do Cognito

    Scenario: Criar Conta Cognito sem fornecer CPF
        Given que eu não forneça um CPF
        When eu executar a função criarContaCognito sem fornecer um CPF
        Then eu devo receber uma mensagem de erro indicando que o CPF é obrigatório

    Scenario: Criar Conta Cognito sem fornecer Nome
        Given que eu não forneça o nome
        When eu executar a função criarContaCognito sem fornecer nome
        Then eu devo receber uma mensagem de erro indicando que o nome é obrigatório


    Scenario: Criar Conta Cognito sem fornecer E-mail
        Given que eu não forneça o e-mail
        When eu executar a função criarContaCognito sem fornecer e-mail
        Then eu devo receber uma mensagem de erro indicando que o e-mail é obrigatório

    Scenario: Criar Conta Cognito com e-mail inválido
        Given que eu forneça um e-mail inválido
        When eu executar a função criarContaCognito com um e-mail inválido
        Then eu devo receber uma mensagem de erro indicando que o e-mail é inválido

    Scenario: Gerar Token com CPF inválido
        Given que eu forneça um CPF inválido
        When eu executar a função gerarToken com um CPF inválido
        Then eu devo receber uma mensagem de erro indicando que o CPF é inválido

    Scenario: Criar Conta Cognito com CPF já existente
        Given que eu forneça um CPF já existente
        When eu executar a função criarContaCognito com um CPF que já possui uma conta no Cognito
        Then eu devo receber uma mensagem indicando que já existe uma conta com esse CPF