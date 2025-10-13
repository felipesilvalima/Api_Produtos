<?php declare(strict_types=1); 

namespace app\controller;

use app\model\ProdutoModel;
use app\validation\ValidationProduto;
use Exception;
use PDOException;

class ProdutoController
{
    public function exibirProdutos()
    {
        try 
        {
           header('Content-Type: application/json; charset=utf-8'); // Define JSON como retorno
    
            $ProdutosModel = new ProdutoModel();
            $listaProdutos = $ProdutosModel->exibirTodosProdutos(); // Busca todos os produtos

                if(empty($listaProdutos)) 
                {
                    http_response_code(404); // Código 404 se não houver produtos
                    echo json_encode([
                        "status" => false,
                        "mensagem" => "Nenhum produto encontrado!!"
                    ]);
                } 
                    else 
                    {
                        http_response_code(200); // Código 200 OK
                        echo json_encode([
                            "status" => true,
                            "dados" => $listaProdutos
                        ]); // Retorna lista de produtos em JSON
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
    
            $ProdutoModel = new ProdutoModel();
            $ProdutoUnico = $ProdutoModel->exibirProdutosId($id); // Busca por um produto
            
            if(isset($id))
            {
                
                if(empty($ProdutoUnico)) 
                {
                    http_response_code(404); // Código 404 se não houver produtos
                    echo json_encode([
                        "status" => false,
                        "mensagem" => "Produto não encontrado!!"
                    ]);
                } 
                    else 
                    {
                        http_response_code(200); // Código 200 OK
                        echo json_encode([
                            "status" => true,
                            "dados" => $ProdutoUnico
                        ]); // Retorna lista de produtos em JSON
                    }         
                
            }
              
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error" . $e->getMessage());
            }
       
    }

    public function insercaoProdutos()
    {
        try 
        {
           header('Content-Type: application/json; charset=utf-8'); // Define JSON como retorno
    
            $ProdutoModel = new ProdutoModel();

            $Produtos = [ // recebendo dados da requsição
              'produto' => (string)$_POST['produto'] ?? null,
              'preco' => (float)$_POST['preco'] ?? 0,
              'quantidade' => (int)$_POST['quantidade'] ?? 0,
              'quantidade_min' => (int)$_POST['quantidade_min'] ?? 0,
              'descricao' => (string)$_POST['descricao'] ?? null,
              'unidade_medida' => $_POST['unidade_medida'] ?? null,
              'categoria_id' => $_POST['categoria_id'] ?? 0,
              'fornecedor_id' => $_POST['fornecedor_id'] ?? 0,
              'usuario_id' => $_POST['usuario_id'] ?? 0,
            ];     

            if(!ValidationProduto::validation($Produtos)) // validação de campos
            {
                 http_response_code(400);
                echo json_encode([
                    "status" => false,
                    "mensagem" => "Precisar prencheer todos os campos!!"
                ]);
                die;
            }

            if(!ProdutoModel::duplicationProduto($Produtos['produto'])) // verificando produto duplicado
            {
                http_response_code(409);
                echo json_encode([
                    "status" => false,
                    "mensagem" => "Esse produto já foi inserido!!"
                ]);
                die;
            }

            $inserir = $ProdutoModel->inserirProdutos($Produtos); // chamar o método para inserir o produto novo

            if($inserir) // inserido com sucesso
            {
                http_response_code(201);
                echo json_encode([
                    "status" => true,
                    "mensagem" => "Produto Inserido com sucesso"
                ]);
            }
                else // error de inserção
                {
                    http_response_code(500);
                    echo json_encode([
                        "status" => false,
                        "mensagem" => "Erro ao Inserido Produto"
                    
                    ]);
                }
            
            
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error" . $e->getMessage());
            }
       
    }

    
}