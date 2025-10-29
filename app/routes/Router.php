<?php declare(strict_types=1); 

namespace app\routes;

use app\controller\AuthController;
use app\controller\ProdutoController;
use app\middleware\AuthMiddleware;
use PDOException;

class Router
{

    public static function execute()
    {
      try 
      {

          $method = $_SERVER['REQUEST_METHOD']; // pegando o método da requisição

          if($method != 'GET' && $method != 'POST' && $method != 'PUT' && $method != 'PATCH' && $method != 'DELETE') // verificando o método
          {
              echo json_encode(["error" => "Método não permitido"]);
              die;
          }

          $autenticacao = new AuthController(); // criando a instacia de produto
          $produtoController = new ProdutoController(); // criando a instacia de produto
         
          $request = $_SERVER['REQUEST_URI']; // Captura a URL

          $request = parse_url($request, PHP_URL_PATH);// Remove parâmetros de query string (ex: ?teste=1)

        if($request === "/login" && $method === "POST")
        {
            $autenticacao->Login();
            die;
        }

          if ($request === "/produtos" || $request === "/produtos/") // Roteamento simples
          {

            if($method === 'PUT' || $method === 'DELETE' || $method === 'PATCH') // se for igual put ou delete endpoint não sera permitido 
            {
              http_response_code(405);
              echo json_encode(["erro" => "Método não permitido para essa ação"]);
              die;
            }
           
              if($method === 'GET')
              {
                AuthMiddleware::Headles();
                $produtoController->exibirProdutos();  // Executar método 
              }
                elseif($method === 'POST')
                {
                  AuthMiddleware::Headles();
                  $produtoController->CadastrarProdutos(); // Executar método
                }

          } 
            elseif (preg_match("/\/produtos\/(\d+)/", $request, $matches)) 
            {
                
                $id = $matches[1]; // Captura o número da rota (ID)
                

                if($method === 'GET')
                {
                  AuthMiddleware::Headles();
                  $produtoController->exibirProdutoId((int)$id); // Retorna a resposta
                }
                  elseif($method === 'PUT' || $method === 'PATCH')
                  {
                      AuthMiddleware::Headles();
                      $produtoController->UpdateProdutos((int)$id);
                  }
                    elseif($method === 'DELETE')
                    {
                        AuthMiddleware::Headles();
                        $produtoController->delete((int)$id);
                    }

                      if($method === 'POST') // se for igual post endpoint não sera permitido 
                      {
                        http_response_code(405);
                        echo json_encode(["erro" => "Método não permitido para essa ação"]);
                        die;
                      }
            
            } 
              else 
              {
                 // Se a rota não existir
                http_response_code(404);
                echo json_encode(["erro" => "Rota não encontrada"]);
              }
                 
      } 
        catch (PDOException $e) 
        {
          echo json_encode(["error" => $e->getMessage()]);
        }
    }
}