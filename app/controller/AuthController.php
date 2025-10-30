<?php declare(strict_types=1); 

namespace app\controller;

use app\model\AuthModel;
use app\validation\AuthValidation;
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
            $credencias = [ // pegando a requisição
                "email" => $_POST['email'] ?? null,
                "senha" => $_POST['senha'] ?? null
            ];

            $response = AuthValidation::validationAllData($credencias);

            if(!empty($responses))
            {
                http_response_code(400);
                echo json_encode(["mensages" => $response]);
                die;
            }
                
            $user = $this->AuthModel->Autentication($credencias); // chamando o método de autenticar

            if($user) // se autenticação for verdadeira
            {
                $token = AuthModel::generateToken($_SESSION['Autenticado']); // gerar token
                
                http_response_code(200);
                echo json_encode([ // saida
                    "status" => true,
                    "mensagem" => "Autenticado com sucesso",
                    "token" => $token
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
                else // se não usuário inválido
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
        session_start(); // start na sessao
        session_reset(); // limpando a sessao
        session_destroy(); // destruindo a sessao

        if(!isset($_SESSION['Autenticado']))
        {
            echo json_encode(["mensagem" => "Usuário não está Autenticado"],JSON_UNESCAPED_UNICODE);
            die;
        }
            
            http_response_code(200);
            echo json_encode(["mensagem" => "Sessão Encerrada"],JSON_UNESCAPED_UNICODE);

    }

    public function Me()
    {
        session_start(); // start na sessao
        
        if(isset($_SESSION['Autenticado']) && !empty($_SESSION['Autenticado'])) // verificando se exister a sessao de autenticação e se ela está vazia
        {
            http_response_code(200);
            echo json_encode(["data" => $_SESSION['Autenticado']], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
            else // se não tiver autenticado
            {
                 http_response_code(403);
                echo json_encode(["mensagem" => "Usuário não está Autenticado"],JSON_UNESCAPED_UNICODE);
            }
    }

    public function Refresh()
    {
        session_start(); // start na sessao

        if(isset($_SESSION['Autenticado'])) // verificando se exister a sessao de autenticação
        {
            $token = AuthModel::generateToken($_SESSION['Autenticado']); // gerando um novo token

            $_SESSION['TotalRefresh'] = 1; // após refresh outro token vai ser inválido

            http_response_code(200);
            echo json_encode(["token Renovado" => $token]);
        }
            else // se não tiver autenticado
            {
                http_response_code(403);
                echo json_encode(["mensagem" => "Usuário não está Autenticado"],JSON_UNESCAPED_UNICODE);
            }
    }
}