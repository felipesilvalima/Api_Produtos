<?php declare(strict_types=1); 

namespace app\controller;

use app\middleware\AuthMiddleware;
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
            $json = file_get_contents('php://input'); // pegando a requisição json 
            $dataDecode = json_decode($json); // decodificando a requisição

            $dataHtml = [ // pegando a requisição html 
                "email" => $_POST['email'] ?? null,
                "senha" => $_POST['senha'] ?? null
            ];

            $dataJson = [ // requisição json decodificada
                "email" => $dataDecode->email ?? null,
                "senha" => $dataDecode->senha ?? null
            ];

            $credencias = array_filter($dataHtml, fn($data) => is_null($data) ) ? $dataJson : $dataHtml;

            $response = AuthValidation::validationAllData($credencias); //validando o email e senha

            if(!empty($responses)) // resposta no caso de dados inválidos
            {
                http_response_code(400);
                echo json_encode(["mensages" => $response]);
                die;
            }
                
            $user = $this->AuthModel->Autentication($credencias); // chamando o método de autenticar

            if($user) // se autenticação for verdadeira
            {
                $token = AuthModel::generateToken($user); // gerar token
                
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
           echo json_encode(["error no controller (Login)" => $e->getMessage()]);
        }


    }

    
    public function Logout()
    {
        $headers = getallheaders(); // pegando os headers;
        $tokenJWT = trim(preg_replace('/^Bearer\s*/i', '', $headers['Authorization'] ?? '')); // pegando o token limpo 
        AuthModel::BlackList($tokenJWT);
        http_response_code(200);

    }

    public function Me()
    {
       $user_info = AuthMiddleware::$user_info;
        
        if(isset($user_info) && $user_info != null) // verificando se exister a sessao de autenticação
        {
            $usuario = $this->AuthModel->BuscarUsuario((int)$user_info->uid);

            if($usuario)
            {
                http_response_code(200);
                echo json_encode(["Usuário" => $usuario]);
            }
                else
                {
                    http_response_code(404);
                    echo json_encode(["error" => "Usuário não encontrado"]);
                }

        }
            else // se não tiver autenticado
            {
                http_response_code(403);
                echo json_encode(["mensagem" => "Usuário não está Autenticado"],JSON_UNESCAPED_UNICODE);
            }
    }

    public function Refresh()
    {
        $user_info = AuthMiddleware::$user_info;
        
        if(isset($user_info) && $user_info != null) // verificando se exister a sessao de autenticação
        {
            
            $user = [
                "id" => $user_info->uid,
                "nome" => $user_info->nome
            ];  
 
            $token = AuthModel::generateToken($user); // gerando um novo token


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