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

          $produto = new ProdutoController();

          // Captura a URL requisitada
          $request = $_SERVER['REQUEST_URI'];

          // Remove parâmetros de query string (ex: ?teste=1)
          $request = parse_url($request, PHP_URL_PATH);

          // Roteamento simples
          if ($request === "/produtos") 
          {
            // Retorna todos os usuários 
            $produto->exibirProdutos();
          } 
            elseif (preg_match("/\/produtos\/(\d+)/", $request, $matches)) 
            {
                // Captura o número da rota (ID)
                $id = $matches[1];

                // Procura o usuário pelo ID 
                $lista = $produto->exibirProdutoId((int)$id);

                if (!empty($lista)) 
                {
                  $produto->exibirProdutoId((int)$id);
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