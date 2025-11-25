<?php declare(strict_types=1);

function BlackList(string $token)
{
    $blacklist[] = $token;

    if (in_array($token, $blacklist)) 
    {
        http_response_code(401);
        echo json_encode([
            "status" => false,
            "mensagem" => "Token inválido!"
        ]);
        die; 
    }
}
