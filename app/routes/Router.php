<?php declare(strict_types=1); 

namespace app\routes;

use app\controller\ProdutoController;
use app\helpers\Request;
use app\helpers\Uri;

use Exception;
use PDOException;

class Router
{

    public static function execute()
    {
      try 
      {
         header('Content-Type: application/json; charset=utf-8'); // configurando a página para retornar json

          $method = $_SERVER['REQUEST_METHOD']; // pegando o método da requisição

          if($method != 'GET' && $method != 'POST') // verificando o método
          {
              echo json_encode(["error" => "Método não permitido"]);
              die;
          }

          $produtoController = new ProdutoController(); // criando a instacia de produto
         
          $request = $_SERVER['REQUEST_URI']; // Captura a URL

          $request = parse_url($request, PHP_URL_PATH);// Remove parâmetros de query string (ex: ?teste=1)

        
          if ($request === "/produtos") // Roteamento simples
          {
           
            if($method === 'GET')
            {
              $produtoController->exibirProdutos();  // Retorna a resposta 
            }
              elseif($method === 'POST')
              {
                $produtoController->insercaoProdutos(); // Retorna a resposta
              }

          } 
            elseif (preg_match("/\/produtos\/(\d+)/", $request, $matches)) 
            {
                
                $id = $matches[1]; // Captura o número da rota (ID)

                if($method === 'GET')
                {
                  $produtoController->exibirProdutoId((int)$id); // Retorna a resposta
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
          echo"error" . $e->getMessage();
        }
    }
}