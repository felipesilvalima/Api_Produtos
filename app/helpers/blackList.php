<?php declare(strict_types=1);

namespace app\helpers;

session_start();
class BlackList
{

    public static function BlackList(?string $token = null)
    {
        $_SESSION['blacklist'][] = $token;
    }
    
    public static function verifyToken($token)
    {
       
        if (in_array($token, $_SESSION['blacklist'] ?? []) && $token != null) 
        {
            http_response_code(401);
            echo json_encode([
                "status" => false,
                "mensagem" => "Token inválido!"
            ]);
            die; 
        }
       
    
    }
}

