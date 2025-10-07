<?php declare(strict_types=1); 

namespace app\controller;

use app\model\ProdutoModel;

require_once __DIR__.'/../model/ProdutoModel.php';

class ProdutoController
{
    public function exibirProdutos()
    {
        header('Content-Type: application/json; charset=utf-8'); // Define JSON como retorno
    
        $Produtos = new ProdutoModel();
        $listaProdutos = $Produtos->exibirTodosProdutos(); // Busca todos os produtos
    
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

}