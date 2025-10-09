<?php declare(strict_types=1); 

namespace app\routes;

use PDOException;

class Router
{

  public static function load(string $controller, string $method) // controller e métodos que vão ser chamados
  {
      try 
      {
        //varificar se controller existe
        
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
            '/produtos' => self::load('ProdutoController', 'exibirProdutos')
          ]

        ];
    }
}