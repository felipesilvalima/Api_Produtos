<?php declare(strict_types=1); 

namespace app\controller;

use app\model\ProdutoModel;
use Exception;
use PDOException;

class ProdutoController
{
    public function exibirProdutos()
    {
        try 
        {
           header('Content-Type: application/json; charset=utf-8'); // Define JSON como retorno
    
            $Produtos = new ProdutoModel();
            $listaProdutos = $Produtos->exibirTodosProdutos(); // Busca todos os produtos
            
                if($_SERVER['REQUEST_METHOD'] === 'GET')
                {

                    if(empty($listaProdutos)) 
                    {
                        http_response_code(404); // Código 404 se não houver produtos
                        echo json_encode(["mensagem" => "Nenhum produto encontrado!!"]);
                    } 
                        else 
                        {
                            http_response_code(200); // Código 200 OK
                            echo json_encode($listaProdutos); // Retorna lista de produtos em JSON
                        }         
                }
                    else
                    {
                        http_response_code(405); // Código 405 se não tiver permissão
                        echo json_encode(["mensagem" => "Método não permitido"]);
                    }
              
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error" . $e->getMessage());
            }
       
    }

    public function exibirProdutoId($id)
    {
        try 
        {
           header('Content-Type: application/json; charset=utf-8'); // Define JSON como retorno
    
            $Produtos = new ProdutoModel();
            $listaProdutos = $Produtos->exibirProdutosId($id); // Busca todos os produtos
            
            if(isset($id))
            {
                if($_SERVER['REQUEST_METHOD'] === 'GET')
                {

                    if(empty($listaProdutos)) 
                    {
                        http_response_code(404); // Código 404 se não houver produtos
                        echo json_encode(["mensagem" => "Produto não encontrado!!"]);
                    } 
                        else 
                        {
                            http_response_code(200); // Código 200 OK
                            echo json_encode($listaProdutos); // Retorna lista de produtos em JSON
                        }         
                }
                    else
                    {
                        http_response_code(405); // Código 405 se não tiver permissão
                        echo json_encode(["mensagem" => "Método não permitido"]);
                    }

            }
              
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error" . $e->getMessage());
            }
       
    }

    
}