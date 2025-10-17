<?php declare(strict_types=1); 

namespace app\controller;

use app\model\ProdutoModel;
use app\validation\ProdutoValidation;
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
                http_response_code(500);
                echo json_encode(["error" => $e->getMessage()]);
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
                http_response_code(500);
                echo json_encode(["error" => $e->getMessage()]);
            }
       
    }

    public function CadastrarProdutos()
    {
        try 
        {
           header('Content-Type: application/json; charset=utf-8'); // Define JSON como retorno
    
            $ProdutoModel = new ProdutoModel();

            $request = [ // recebendo dados da requsição
              'produto' => $_POST['produto'] ?? null,
              'preco' => $_POST['preco'] ?? 0,
              'quantidade' => $_POST['quantidade'] ?? 0,
              'quantidade_min' => $_POST['quantidade_min'] ?? 0,
              'descricao' => $_POST['descricao'] ?? null,
              'unidade_medida' => $_POST['unidade_medida'] ?? null,
              'categoria_id' => $_POST['categoria_id'] ?? 0,
              'fornecedor_id' => $_POST['fornecedor_id'] ?? 0
            ];     

                $response = ProdutoValidation::validationAllData($request);
                
                if($response != null) // validação de dados
                {        
                    http_response_code(400);
                    echo json_encode(["mensagem" => $response]);
                    die;
                }

                    $inserir = $ProdutoModel->inserirProdutos($request); // chamar o método para inserir o produto novo

                        if($inserir) // inserido com sucesso
                        {
                            http_response_code(201);
                            echo json_encode([
                                "status" => true,
                                "mensagem" => "Produto Inserido com sucesso",
                                "data" => $request
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
                http_response_code(500);
                echo json_encode(["error" => $e->getMessage()]);
            }
       
    }


    public function UpdateProdutos($id)
    {
        try 
        {
            header('Content-Type: application/json; charset=UTF-8');// cabeçalho da resposta

            $ProdutoModel = new ProdutoModel();

            $request = [ // recebendo dados da requsição
              'produto' => $_REQUEST['produto'] ?? null,
              'preco' => $_REQUEST['preco'] ?? 0,
              'quantidade' => $_REQUEST['quantidade'] ?? 0,
              'quantidade_min' => $_REQUEST['quantidade_min'] ?? 0,
              'descricao' => $_REQUEST['descricao'] ?? null,
              'unidade_medida' => $_REQUEST['unidade_medida'] ?? null,
              'categoria_id' => $_REQUEST['categoria_id'] ?? 0,
              'fornecedor_id' => $_REQUEST['fornecedor_id'] ?? 0
            ];

            $response = ProdutoValidation::validationAllData($request);

            if($response != null) // validação dos dados
            {
                http_response_code(400);
                echo json_encode(["mensagem" => $response]);
                die;
            }

                $update = $ProdutoModel->UpdateProdutos($request, $id); // chamando o método para atualizar

                    if($update) // atualizado com sucesso
                    {
                        http_response_code(200);
                        echo json_encode([
                            "status" => true,
                            "mensagem" => "Produto Atualizado com sucesso",
                            "data" => $request
                        ]);
                    }
                        else // error de atualizar
                        {
                            http_response_code(500);
                            echo json_encode([
                                "status" => false,
                                "mensagem" => "Erro ao Atualizar Produto"
                    
                            ]);
                        }

        } 
            catch (PDOException $e) 
            {
                http_response_code(500);
                echo json_encode(["error" => $e->getMessage()]);
            }
    }

    
}