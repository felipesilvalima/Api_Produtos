<?php declare(strict_types=1); 

namespace app\controller;

use app\model\AuthModel;
use PDOException;

class AuthController
{
    private $AuthModel;

    public function __construct()
    {
        $this->AuthModel = new AuthModel;
    }

    public function Login()
    {
       try 
       {
            $credencias = [
                "email" => $_POST['email'] ?? null,
                "senha" => $_POST['senha'] ?? null
            ];
                
            $user = $this->AuthModel->Autentication($credencias);

            if(!empty($user))
            {
                $token = AuthModel::generateToken($user);
                
                http_response_code(200);
                echo json_encode([
                    "status" => true,
                    "mensagem" => "Autenticado com sucesso",
                    "token" => $token
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
                else
                {
                    http_response_code(403);
                    echo json_encode([
                        "status" => false,
                        "mensagem" => "Usuário ou Senha inválida"
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }


       } 
        catch (PDOException $e) // error no servidor
        {
           http_response_code(500);
           echo "error no controller (Login)" . $e->getMessage();
        }


    }

    
    public function Logout()
    {
        session_start();
        session_reset();
        session_destroy();
    }
}