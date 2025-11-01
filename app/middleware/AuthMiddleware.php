<?php declare(strict_types=1); 

namespace app\middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;

require_once __DIR__. '/../../config/env.php';
class AuthMiddleware
{
     public static function Headles()
    {
        try 
        {
            session_start();

            $headers = getallheaders(); // pegando os headers;
            $tokenJWT = trim(preg_replace('/^Bearer\s*/i', '', $headers['Authorization'] ?? '')); // pegando o token limpo

             if(!isset($_SESSION['Autenticado']))
             {
                echo json_encode(["mensagem" => "Usuário não está Autenticado"],JSON_UNESCAPED_UNICODE);
                die;
             }
            
                if (empty($tokenJWT) ) // verificar se o token tá vázio
                {
                    http_response_code(401);
                    echo json_encode([
                        "status" => false,
                        "mensagem" => "Token ausente"
                    ]);
                    die;
                }

            // Decodificar e validar token
            $dados = JWT::decode($tokenJWT, new Key($_ENV['API_KEY'], 'HS256'));

                if(isset($_SESSION['TotalRefresh']) && $_SESSION['TotalRefresh'] > 0) // verificar sé foi criado outro token
                {
                    if(isset($dados) && !empty($dados))
                    {

                        http_response_code(403);
                        echo json_encode([
                            "status" => false,
                            "mensagem" => "Token inválido!"
                        ]);
    
                        unset($_SESSION['TotalRefresh']); // limpa sessão
                        unset($dados); // limpa token
                        die; 
                    }
                }
            
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