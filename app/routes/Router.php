<?php declare(strict_types=1); 

namespace app\routes;

use app\helpers\Request;
use app\helpers\Uri;

use Exception;
use PDOException;

class Router
{

  public static function load(string $controller, string $method) // controller e métodos que vão ser chamados
  {
      try 
      {
        //varificar se controller existe
        $controllerNamespace = 'app\\controller' . '\\' . $controller;

        if(!class_exists($controllerNamespace))
        {
            throw new Exception("O controller {$controller} não existe");
        }

          $controllerIstancia = new $controllerNamespace; //criando istancia

          //varificar se existe método no controller
          if(!method_exists($controllerIstancia, $method))
          {
            throw new Exception("O Método {$method} não existe no controller {$controller}");
          }
            
            $controllerIstancia->$method(); // chamando o método
        
      } 
        catch (PDOException $e) 
        {
            echo"error" . $e->getMessage();
        }  
  }


    public static function Routes(): array // Rotas recebendo controller e método 
    {
        return [

          'GET' => [
            '/produtos' => fn() => self::load('ProdutoController', 'exibirProdutos')
          ]

        ];
    }

    public static function execute()
    {
      try 
      {
         header('Content-Type: application/json; charset=utf-8'); // configurando a página para retornar json

          $routes = self::Routes(); // rotas
          $request =  $_SERVER['REQUEST_METHOD']; // pegando o método da requisição
          $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // pegando a uri da requisição

          if(!isset($routes[$request])) // se método não existir
          {
            echo json_encode(["error" => "Método não permitido"]);
            die;
          }

            $router = $routes[$request][$uriPath] ?? null; // pegando a uri das rotas
            
            if(!isset($router)) // se uri não existir dentro das rotas
            {
              echo json_encode(["error" => "Rota não encontrada"]);
              die;
            }

              if(!is_callable($router)) // se a uri não for uma função;
              {
                echo json_encode(["error" => "A rota {$router} não e uma função"]);
                die;
              }

              $router(); // executar o método da uri
                 
      } 
        catch (PDOException $e) 
        {
          echo"error" . $e->getMessage();
        }
    }
}