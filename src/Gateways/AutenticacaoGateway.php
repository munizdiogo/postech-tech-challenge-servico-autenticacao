<?php

namespace Autenticacao\Gateways;

require "./src/Interfaces/Gateways/AutenticacaoGatewayInterface.php";

use Autenticacao\Interfaces\Gateways\AutenticacaoGatewayInterface;

class AutenticacaoGateway implements AutenticacaoGatewayInterface
{

    public function gerarToken($cpf)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://s901dmj5n4.execute-api.us-east-1.amazonaws.com/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>
            '{
                "cpf": "' . str_replace([".", "-"], "", $cpf) . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        http_response_code(200);
        return $response;
    }

    public function criarContaCognito($cpf, $nome, $email)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://s901dmj5n4.execute-api.us-east-1.amazonaws.com/criar-usuario',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                        "email": "' . $email . '",
                        "name": "' . $nome . '",
                        "cpf": "' . $cpf . '"
                    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        http_response_code(200);
        return $response;
    }
}
