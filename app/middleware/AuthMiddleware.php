<?php declare(strict_types=1); 

namespace app\middleware;

use app\model\AuthModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;

require_once __DIR__. '/../../config/env.php';
class AuthMiddleware
{
    public static $user_info = null;

    public static function Headles()
    {
        try 
        {
    
            $headers = getallheaders(); // pegando os headers;
            $tokenJWT = trim(preg_replace('/^Bearer\s*/i', '', $headers['Authorization'] ?? '')); // pegando o token limpo


                if (empty($tokenJWT) ) // verificar se o token tá vázio
                {
                    http_response_code(401);
                    echo json_encode([
                        "status" => false,
                        "mensagem" => "Token ausente"
                    ]);
                    die;
                }

                if(AuthModel::VerifyToken($tokenJWT))
                {
                    http_response_code(401);
                    echo json_encode([
                        "status" => false,
                        "mensagem" => "Token inválido"
                    ]);
                    die;
                }

            // Decodificar e validar token
            $dados = JWT::decode($tokenJWT, new Key($_ENV['API_KEY'], 'HS256'));
            self::$user_info = $dados;
               
        }
            catch (\Firebase\JWT\ExpiredException) 
            {
                http_response_code(401);
                        echo json_encode([
                            "status" => false,
                            "mensagem" => "Token expirado!"
                        ]);
                        die;
                
            } 
                catch (\Firebase\JWT\SignatureInvalidException) 
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