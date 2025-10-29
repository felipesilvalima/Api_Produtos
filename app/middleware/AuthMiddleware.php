<?php declare(strict_types=1); 

namespace app\middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;

class AuthMiddleware
{
     public static function Headles()
    {
        try 
        {
           session_start();

            $headers = getallheaders(); // pegando os headers;
            $tokenJWT = trim(str_replace('Bearer ', '', $headers['Authorization'])); // pegando o token limpo
            $privateKey = '61921872314'; // chave privada


            if (empty($tokenJWT)) // verificar se o token tá vázio
            {
                http_response_code(401);
                echo json_encode([
                    "status" => false,
                    "mensagem" => "Token ausente"
                ]);
                die;
            }

            // Decodificar e validar token
            $dados = JWT::decode($tokenJWT, new Key($privateKey, 'HS256'));
            
        }
            catch (\Firebase\JWT\ExpiredException $e) 
            {
                http_response_code(401);
                        echo json_encode([
                            "status" => false,
                            "mensagem" => "Token expirado!"
                        ]);
                        die;
                
            } 
                catch (\Firebase\JWT\SignatureInvalidException $e) 
                {
                    http_response_code(401);
                        echo json_encode([
                            "status" => false,
                            "mensagem" => "Assinatura inválida!"
                        ]);
                        die; 
                }
                    catch (PDOException) 
                    {
                        http_response_code(403);
                        echo json_encode([
                            "status" => false,
                            "mensagem" => "Token inválido!"
                        ]);
                        die;  
                    }

    }

}