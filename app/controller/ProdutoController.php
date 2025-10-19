<?php declare(strict_types=1); 

namespace app\controller;

use app\helpers\Methods;
use app\model\ProdutoModel;
use app\validation\ProdutoValidation;
use Exception;
use PDOException;

class ProdutoController
{
    private $ProdutoModel;
    
    public function __construct()
    {
        $this->ProdutoModel = new ProdutoModel();
    }

    public function exibirProdutos()
    {
        try 
        {
           header('Content-Type: application/json; charset=utf-8'); // Define JSON como retorno
    
            $listaProdutos = $this->ProdutoModel->exibirTodosProdutos(); // Busca todos os produtos

                if(empty($listaProdutos)) 
                {
                    http_response_code(404); // Código 404 se não houver produtos
                    echo json_encode([
                        "status" => false,
                        "mensagem" => "Nenhum Recurso encontrado"
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
            catch (PDOException $e) // error interno
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
    
            $ProdutoId = $this->ProdutoModel->exibirProdutosId($id); // Busca por um produto
            
            if(isset($id))
            {
                
                if(!empty($ProdutoId)) 
                {
                    http_response_code(200); // Código 200 OK
                        echo json_encode([
                            "status" => true,
                            "dados" => $ProdutoId
                        ]); // Retorna lista de produtos em JSON
                } 
                    else
                    {
                       http_response_code(404); // Código 404 se não houver produtos
                        echo json_encode([
                            "status" => false,
                            "mensagem" => "Recurso não encontrado"
                        ]); 
                    }         
                
            }
              
        } 
            catch (PDOException $e) // error interno 
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

                    $inserir = $this->ProdutoModel->inserirProdutos($request); // chamar o método para inserir o produto novo

                        if($inserir) // inserido com sucesso
                        {
                            http_response_code(201);
                            echo json_encode([
                                "status" => true,
                                "mensagem" => "Produto Inserido com sucesso",
                                "data" => $request
                            ]);
                        }
                            else // error interno de model
                            {
                                http_response_code(500); 
                                echo json_encode([
                                    "status" => false,
                                    "mensagem" => "Erro ao Inserido Produto"
                    
                                ]);
                            }
            
            
        } 
            catch (PDOException $e) // error interno 
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

            if(isset($id))
            {

                $request = Methods::requestPut(); // pegando requisição PUT

                if(is_array($request)) // se request e um array adiciono uma chave com valor do id
                {
                    $request["id"] = $id;
                }
    
                $isExistisID = $this->ProdutoModel->isExistID($id); // verificando se existir o recurso solicitado
                
                if(!$isExistisID) // se não existir
                {
                    echo json_encode(["error" => "Impossivel realizar atualização. O recurso solicitado não existe"]);
                    die;
                }
                
                $response = ProdutoValidation::validationAllData($request); // validando os dados
    
                if($response != null) // se for diferente de null 
                {
                    http_response_code(400);
                    echo json_encode(["mensagem" => $response]);
                    die;
                }
    
                    $update = $this->ProdutoModel->UpdateProdutos($request, $id); // chamando o método para atualizar
    
                        if($update) // atualizado com sucesso
                        {
                            http_response_code(200);
                            echo json_encode([
                                "status" => true,
                                "mensagem" => "Produto Atualizado com sucesso",
                                "data" => $request
                            ]);
                        }
                            else
                            {
                                http_response_code(500);
                                echo json_encode([
                                    "status" => false,
                                    "mensagem" => "Erro ao Atualizar Produto"
                        
                                ]);
                            }
            }

        } 
            catch (PDOException $e) // error interno
            {
                http_response_code(500);
                echo json_encode(["error" => $e->getMessage()]);
            }
    }

    public function deleteProdutos($id)
    {
        header('Content-Type: application/json; chasert=UTF-8');

        try 
        {
            if(isset($id))
            {
                $isExistisID = $this->ProdutoModel->isExistID($id); // verificando se existir o recurso solicitado
                
                if(!$isExistisID) // se não existir
                {
                    echo json_encode(["error" => "Impossivel realizar Remoção. O recurso solicitado não existe"]);
                    die;
                }

            }

        } 
            catch (PDOException $e) 
            {
                http_response_code(500);
                echo json_encode(["error" => $e->getMessage()]);  
            }
    }

    
}