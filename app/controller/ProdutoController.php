<?php declare(strict_types=1); 

namespace app\controller;

use app\helpers\Methods;
use app\model\ProdutoModel;
use app\validation\AtributesValidation;
use app\validation\ProdutoValidation;
use Exception;
use PDOException;

class ProdutoController
{
    private $ProdutoModel;
    
    public function __construct($model)
    {
        $this->ProdutoModel = $model;
    }
    
    public function exibirProdutos()
    {
        try 
        {   
            $produtos = [];    

            if(isset($_GET['atributos']))// filtragem 
            {
                
                $atributos = $_GET['atributos'];
                $atributos_categoria = $_GET['atributos_categoria'] ?? null;
                $atributos_fornecedor = $_GET['atributos_fornecedor'] ?? null;
                $filtro = $_GET['filtro'] ?? null;

               $response = AtributesValidation::AttributesValidation((string)$atributos, (string)$atributos_categoria, (string)$atributos_fornecedor);

               if($response != null)
                {
                    echo json_encode($response);
                    die;
                }
            
                
                $produtos =  $this->ProdutoModel->filterAttributes((string)$atributos, (string)$atributos_categoria, (string)$atributos_fornecedor, (string)$filtro); // Busca atributos especificos

            }
                else
                {
                  $produtos =  $this->ProdutoModel->exibirTodosProdutos(); // Busca todos os produtos
                }

                    if(!empty($produtos)) 
                    {   
                        http_response_code(200); // Código 200 OK
                        echo json_encode([
                            "status" => true,
                            "datas" => $produtos
                        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); // Retorna lista de produtos em JSON
                    } 
                        else 
                        {
                            http_response_code(404); // Código 404 se não houver produtos
                            echo json_encode([
                                "status" => false,
                                "mensagem" => "Nenhum Recurso encontrado",
                            ]);  
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
    
            if(isset($id))
            {

                if(!ProdutoModel::isExistAtributte($id,"id","produtos"))
                {
                    http_response_code(404);
                    echo json_encode(["error" => "Recurso não encontrado"]);
                    die;
                } 
                    else 
                    {              
                        $ProdutoId = $this->ProdutoModel->exibirProdutosId($id); // Busca por um produto
    
                            if(!empty($ProdutoId))
                            {
                                http_response_code(200); // Código 200 OK
                                echo json_encode([
                                    "status" => true,
                                    "data" => $ProdutoId
                                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); // Retorna lista de produtos em JSON
                            }
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

            $request = [ // recebendo dados da requsição
              'produto' => $_POST['produto'] ?? null,
              'preco' => $_POST['preco'] ?? 0,
              'quantidade' => $_POST['quantidade'] ?? 0,
              'quantidade_min' => $_POST['quantidade_min'] ?? 0,
              'descricao' => $_POST['descricao'] ?? null,
              'unidade_medida' => $_POST['unidade_medida'] ?? null,
              'categoria_id' => $_POST['categoria_id'] ?? 0,
              'fornecedor_id' => $_POST['fornecedor_id'] ?? 0,
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
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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

            if(isset($id))
            {

                $request = Methods::requestPut(); // pegando requisição PUT

                $notRequestID = $request; // pegando a penas a requsição sem id

                is_array($request) ? $request = ["id" => $id] + $request : null; // se request e um array adiciono uma chave com valor do id em request
                
                if(!ProdutoModel::isExistAtributte($id,"id","produtos")) // verificando se existir o recurso solicitado
                {
                    http_response_code(404);
                    echo json_encode(["error" => "Impossivel realizar atualização. O recurso solicitado não existe"]);
                    die;
                }

                if($_SERVER['REQUEST_METHOD'] === 'PATCH') // se método for PATCH
                {

                    if(empty($notRequestID)) // verificar se tem algum campo preenchdio 
                    {
                        echo json_encode(["error" => "Nenhum campo está preenchido"]);
                        die;
                    }
                    
                    $responses = ProdutoValidation::validationAllData($request); // validação do dados
                    
                    $chaveRequest = array_keys($request); // pegar chaves da requição
                    

                    foreach($chaveRequest as $chave)
                    {
                                
                        if($chave != 'id' && isset($responses[$chave])) // se chave não for um id e se a resposta da validação existir
                        {
                            $respostasDinamicas[$chave] = $responses[$chave]; // regras Dinamicas receber a resposta da validação
                               
                        }
                    }


                    if(!empty($respostasDinamicas)) // se as respostaDinamicas não forem vazias  
                    {
                        http_response_code(400);
                        echo json_encode($respostasDinamicas); // return json de respostaDinamicas
                        die;
                    }

                     $updateParcial = $this->ProdutoModel->UpdateParcialProdutos($request, $id); // atualizar o recurso parcialmente
    
                            if($updateParcial) // atualizado com sucesso
                            {
                                http_response_code(200);
                                echo json_encode([
                                    "status" => true,
                                    "mensagem" => "Produto Atualizado com sucesso",
                                    "data" => $request
                                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
                    if($_SERVER['REQUEST_METHOD'] === 'PUT') // se método for  PUT
                    {

                        $response = ProdutoValidation::validationAllData($request); // validando os dados
            
                        if($response != null) // se for diferente de null 
                        {
                            http_response_code(400);
                            echo json_encode(["mensagem" => $response]);
                            die;
                        }
            
                        $update = $this->ProdutoModel->UpdateProdutos($request, $id); // atualizar o recurso
    
                            if($update) // atualizado com sucesso
                            {
                                http_response_code(200);
                                echo json_encode([
                                    "status" => true,
                                    "mensagem" => "Produto Atualizado com sucesso",
                                    "data" => $request
                                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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

        } 
            catch (PDOException $e) // error interno
            {
                http_response_code(500);
                echo json_encode(["error" => $e->getMessage()]);
            }
    }

    public function delete(int $id)
    { 
        try 
        {

            $deleteDates = $this->ProdutoModel->exibirProdutosId($id); // dados que vão ser removidos

            if(isset($id))
            {
                
                if(!ProdutoModel::isExistAtributte($id,"id","produtos"))  // verificando se existir o recurso solicitado
                {
                    http_response_code(404);
                    echo json_encode(["error" => "Impossivel realizar Remoção. O recurso solicitado não existe"]);
                    die;
                }

                $remover = $this->ProdutoModel->deleteProduto($id); // fazer a remoção dos dados

                if($remover) // removido com sucesso
                {
                    http_response_code(200);
                    echo json_encode([
                        "status" => true,
                        "mensagem" => "Produto removido com sucesso",
                        "data" => $deleteDates
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                    die;
                }
                    else // error ao remover produto
                    {
                        http_response_code(500);
                         echo json_encode([
                            "status" => false,
                            "mensagem" => "Erro ao Remover Produto"   
                        ]);

                    }


            }

        } 
            catch (PDOException $e) 
            {
                http_response_code(500);
                echo json_encode(["error " => $e->getMessage()]);  
            }
    }

    public function entradaEstoque(int $idProduto)
    {
        try
        {
            if(is_numeric($idProduto) && !empty($idProduto))
            {
                $quantidadeEntrada = Methods::requestPut();


                $request = ProdutoValidation::validationAllData($quantidadeEntrada);

                    if(isset($request['quantidade']))
                    {

                        foreach($request['quantidade'] as $response)
                        {
                                    
                            $respostasDinamicas['quantidade'] = $response;
                            
                        }
    
                        if(!empty($respostasDinamicas)) // se as respostaDinamicas não forem vazias  
                        {
                            http_response_code(400);
                            echo json_encode($respostasDinamicas);
                            die;
                        }
                    }

                    $produto = $this->ProdutoModel->exibirProdutosId((int)$idProduto);

                    if($produto)
                    {
                         
                        $quantidadeTotal = $this->ProdutoModel->entrada_quantidade((int)$produto['id'],(int)$quantidadeEntrada['quantidade'], (int)$produto['quantidade']);

                        http_response_code(200);
                        echo json_encode([
                            "status" => true,
                            "mensagem" => "Quantidade de produto atualizada!!",
                            "quantidade" => $quantidadeTotal
                        ]);
                        
                    }
                        else
                        {
                            http_response_code(404);
                            echo json_encode([
                                "status" => false,
                                "mensagem" => "Produto não encontrado"
                            ]);
                        }

            }    
            
        }
            catch(PDOException $error)
            {
                http_response_code(500);
                echo json_encode([
                    "error" => $error->getMessage()
                ]);
            }
    }

    
}