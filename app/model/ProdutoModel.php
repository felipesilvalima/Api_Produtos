<?php declare(strict_types=1); 

namespace app\model;

use app\helpers\Attributes;
use app\middleware\AuthMiddleware;
use ErrorException;
use Exception;
use PDO;
use PDOException;
class ProdutoModel
{

    private $id;
    private $produto;
    private $preco;
    private $quantidade;
    private $descricao;
    private $quantidade_min;
    private $unidade_medida;
    private $categoria_id;
    private $fornecedor_id;
    private $usuario_id;
    
    private static $conexao;

    public function __construct($db)
    {
        self::$conexao = $db;
    }

    public function exibirTodosProdutos()
    {
        try 
        {

            $sql = "SELECT 
            P.*,
            P.id AS p_id,
            P.descricao AS p_desc,
            C.*,
            C.id AS c_id,
            C.descricao AS c_desc,
            F.*,
            F.id As f_id  
            FROM Produtos AS P INNER JOIN categoria AS C ON P.categoria_id = C.id  INNER JOIN fornecedor AS F ON P.fornecedor_id = F.id"; // SQL para listar todos os produtos
            $stm = self::$conexao->prepare($sql);  // Prepara a query
            $stm->execute();                            // Executa a query

            $listarProdutos = $stm->fetchAll(PDO::FETCH_OBJ); // Pega todos os resultados como objetos

            if(!$listarProdutos) // se não existir a linha retorne array vazio
            {
                return [];
            }

            foreach ($listarProdutos as $line) 
            {
                $datas[] = [
                    'id' => $line->p_id,
                    'categoria_id' => $line->categoria_id,
                    'fornecedor_id' => $line->fornecedor_id,
                    'produto' => $line->produto,
                    'preco' => $line->preco,
                    'quantidade' => $line->quantidade_max,
                    'quantidade_min' => $line->quantidade_min,
                    'descricao' => $line->p_desc,
                    'unidade_medida' => $line->unidade_medida,
                    'categoria' => [
                        'id' => $line->c_id,
                        'categoria' => $line->categoria,
                        'descricao' => $line->c_desc,
                    ],
                    'fornecedor' => [
                        'id' => $line->f_id,
                        'fornecedor' => $line->fornecedor,
                        'cpf' => $line->cpf,
                        'telefone' => $line->telefone,
                        'endereco' => $line->endereco,
                    ]
                 ];
            }

            
            return $datas; // Retorna o array de objetos
        
        } 
            catch (PDOException $e) 
            {
               throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
                finally 
                {
                    Conexao::closeConexao(); //fechando conexão
                }
    }

    public function exibirProdutosId(int $id)
    {
        try 
        {
            $sql = "SELECT 
            P.*, 
            P.id AS p_id,
            P.descricao AS p_desc,
            C.*,
            C.id AS c_id,
            C.descricao AS c_desc,
            F.*,
            F.id As f_id  
            FROM Produtos AS P INNER JOIN categoria AS C ON P.categoria_id = C.id  INNER JOIN fornecedor AS F ON P.fornecedor_id = F.id WHERE P.id = :id"; // SQL para listar todos os produtos
            $stm = self::$conexao->prepare($sql);  // Prepara a query
            $stm->bindParam(':id', $id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query

            $line = $stm->fetch(PDO::FETCH_OBJ); // Pega o resultados como objetos

            if(!$line) // se não existir a linha retorne array vazio
            {
                return [];
            }

                $data = [
                    'id' => $line->p_id,
                    'categoria_id' => $line->categoria_id,
                    'fornecedor_id' => $line->fornecedor_id,
                    'produto' => $line->produto,
                    'preco' => $line->preco,
                    'quantidade' => $line->quantidade_max,
                    'quantidade_min' => $line->quantidade_min,
                    'descricao' => $line->p_desc,
                    'unidade_medida' => $line->unidade_medida,
                    'categoria' => [
                        'id' => $line->c_id,
                        'categoria' => $line->categoria,
                        'descricao' => $line->c_desc,
                    ],
                    'fornecedor' => [
                        'id' => $line->f_id,
                        'fornecedor' => $line->fornecedor,
                        'cpf' => $line->cpf,
                        'telefone' => $line->telefone,
                        'endereco' => $line->endereco,
                    ]
                 ];
            
            return $data;  // Retorna o array de objeto
        
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
                finally 
                {
                    Conexao::closeConexao(); //fechando conexão
                }
    }

    public function inserirProdutos(array $request)
    {
        try 
        {
            $this->produto = ucfirst(strtolower($request['produto'])) ?? null;
            $this->descricao = ucfirst(strtolower($request['descricao'])) ?? null;
            $this->preco = $request['preco'] ?? 0;
            $this->quantidade = $request['quantidade'] ?? 0;
            $this->quantidade_min = $request['quantidade_min'] ?? 0;
            $this->unidade_medida = ucfirst(strtolower($request['unidade_medida'])) ?? null;
            $this->categoria_id = $request['categoria_id'] ?? 0;
            $this->fornecedor_id = $request['fornecedor_id'] ?? 0;
            $this->usuario_id =  AuthMiddleware::$user_info->uid;
            

            $sql = "INSERT INTO 
            produtos(produto,descricao,preco,quantidade_max,quantidade_min,unidade_medida,categoria_id,fornecedor_id,usuario_id)
            VALUES(:produto, :descricao, :preco, :quantidade, :quantidade_min, :unidade_medida, :categoria_id, :fornecedor_id, :usuario_id)"; // SQL para listar todos os produtos
            $stm = self::$conexao->prepare($sql);  // Prepara a query
            $stm->bindParam(':produto', $this->produto, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':descricao', $this->descricao, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':preco', $this->preco, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':quantidade', $this->quantidade, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':quantidade_min', $this->quantidade_min, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':unidade_medida', $this->unidade_medida, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':categoria_id', $this->categoria_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':fornecedor_id', $this->fornecedor_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindValue(':usuario_id', (int)$this->usuario_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query


            if($stm)
            {
                return true;
            }
            
            return false;
        
        } 
            catch (PDOException $e) 
            {
               throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
                finally 
                {
                    Conexao::closeConexao(); //fechando conexão
                }
    }

    public function UpdateProdutos(array $request, int $id)
    {
         try 
        {
            $this->id = $id;
            $this->produto = ucfirst(strtolower($request['produto'])) ?? null;
            $this->descricao = ucfirst(strtolower($request['descricao'])) ?? null;
            $this->preco = $request['preco'] ?? 0.00;
            $this->quantidade = $request['quantidade'] ?? 0;
            $this->quantidade_min = $request['quantidade_min'] ?? 0;
            $this->unidade_medida =  ucfirst(strtolower($request['unidade_medida'])) ?? null;
            $this->categoria_id = $request['categoria_id'] ?? 0;
            $this->fornecedor_id = $request['fornecedor_id'] ?? 0;
            $this->usuario_id =  AuthMiddleware::$user_info->uid;

            $sql = "UPDATE produtos
            SET produto=:produto, descricao=:descricao, preco=:preco, quantidade_max=:quantidade, quantidade_min=:quantidade_min, unidade_medida=:unidade_medida, categoria_id=:categoria_id, fornecedor_id=:fornecedor_id, usuario_id=:usuario_id WHERE id = :id"; // SQL para listar todos os produtos
            $stm = self::$conexao->prepare($sql);  // Prepara a query
            $stm->bindParam(':id', $this->id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':produto', $this->produto, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':descricao', $this->descricao, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':preco', $this->preco, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':quantidade', $this->quantidade, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':quantidade_min', $this->quantidade_min, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':unidade_medida', $this->unidade_medida, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':categoria_id', $this->categoria_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':fornecedor_id', $this->fornecedor_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindValue(':usuario_id', (int)$this->usuario_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query

            if($stm)
            {
                return true;
            }

            return false;
        
        } 
            catch (PDOException $e) 
            {
               throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
                finally 
                {
                    Conexao::closeConexao(); //fechando conexão
                }
    }

     public function UpdateParcialProdutos(array $request, int $id)
    {
         try 
        {
            $values = $this->exibirProdutosId($id); // pegar os dados do id


            $this->id = $id;
            $this->produto = isset($request['produto']) && !empty($request['produto']) ? ucfirst(strtolower($request['produto'])) : $values['produto'];
            $this->descricao = isset($request['descricao']) && !empty($request['descricao']) ? ucfirst(strtolower($request['descricao'])) : $values['descricao'];
            $this->preco =  isset($request['preco']) && !empty($request['preco']) ? $request['preco'] : $values['preco'];
            $this->quantidade = isset($request['quantidade']) && !empty($request['quantidade']) ? $request['quantidade'] : $values['quantidade'];
            $this->quantidade_min = isset($request['quantidade_min']) && !empty($request['quantidade_min']) ? $request['quantidade_min'] : $values['quantidade_min'];
            $this->unidade_medida =  isset($request['unidade_medida']) && !empty($request['unidade_medida']) ? ucfirst(strtolower($request['unidade_medida'])) : $values['unidade_medida'];
            $this->categoria_id =  isset($request['categoria_id']) && !empty($request['categoria_id']) ? $request['categoria_id'] : $values['categoria_id'];
            $this->fornecedor_id = isset($request['categofornecedor_idria_id']) && !empty($request['fornecedor_id']) ? $request['fornecedor_id'] : $values['fornecedor_id'];
            $this->usuario_id =  AuthMiddleware::$user_info->uid;

            $sql = "UPDATE produtos
            SET produto=:produto, descricao=:descricao, preco=:preco, quantidade_max=:quantidade, quantidade_min=:quantidade_min, unidade_medida=:unidade_medida, categoria_id=:categoria_id, fornecedor_id=:fornecedor_id, usuario_id=:usuario_id WHERE id = :id"; // SQL para listar todos os produtos
            $stm = self::$conexao->prepare($sql);  // Prepara a query
            $stm->bindParam(':id', $this->id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':produto', $this->produto, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':descricao', $this->descricao, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':preco', $this->preco, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':quantidade', $this->quantidade, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':quantidade_min', $this->quantidade_min, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':unidade_medida', $this->unidade_medida, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':categoria_id', $this->categoria_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindParam(':fornecedor_id', $this->fornecedor_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->bindValue(':usuario_id', (int)$this->usuario_id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query

            if($stm)
            {
                return true;
            }

            return false;
        
        } 
            catch (PDOException $e) 
            {
               throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
                finally 
                {
                    Conexao::closeConexao(); //fechando conexão
                }
    }

    public function deleteProduto(int $id)
    {
        try
        {

            $sql = "DELETE FROM produtos WHERE id = :id"; // SQL para remover uma listar de produto
            $stm = self::$conexao->prepare($sql); // Prepara a query
            $stm->bindParam(':id', $id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query

            if($stm)
            {
                return true;
            }
    
            return false;
        }
            catch(PDOException $e)
            {
                throw new Exception("error no banco de dados" . $e->getMessage());  // Lança exceção em caso de erro
            }
                finally 
                {
                    Conexao::closeConexao(); //fechando conexão
                }
    }

    public static function isExistProduto(string $produto , ?int $id = null)
    {
        try 
        {
            $sql = "SELECT produto FROM produtos WHERE produto = :produto AND id != :id"; // SQL para verificar produto
            $stm = self::$conexao->prepare($sql);  // Prepara a query
            $stm->bindParam(':produto', $produto, PDO::PARAM_STR); // passando o parâmetro
            $stm->bindParam(':id', $id, PDO::PARAM_INT); // passando o parâmetro
            $stm->execute(); // Executa a query

            if($stm->rowCount() > 0)
            {
                return false;
            }

            return true;
            
        
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
                finally 
                {
                    Conexao::closeConexao(); //fechando conexão
                }
    }

    public static function isExistAtributte(int $value,string $atributo, string $table)
    {
        try
        {
            $sql = "SELECT id FROM $table WHERE $atributo = :value";
            $stm = self::$conexao->prepare($sql);
            $stm->bindParam(':value', $value);
            $stm->execute(); 

            $verificarCategoria = $stm->fetch(PDO::FETCH_OBJ);

            if(empty($verificarCategoria))
            {
                return false;
            }
    
            return true;
        } 
            catch(PDOException $e)
            {
                throw new Exception("error no banco de dados" . $e->getMessage());
            }
                finally 
                {
                    Conexao::closeConexao(); //fechando conexão
                }
    }
    
    public function filterAttributes(string $atributos,  string $atributos_categoria, string $atributos_fornecedor, string $filtro)
    {
        try 
        {
                   
            $sql = Attributes::QueryFilter($atributos, $atributos_categoria, $atributos_fornecedor, $filtro);               
            $stm = self::$conexao->prepare($sql);  // Prepara a query
            $stm->execute(); // Executa a query
            
            $lines = $stm->fetchall(PDO::FETCH_OBJ); // Pega o resultados como objetos

            if(!$lines) // se não existir a linha retorne array vazio
            {
                return [];
            }
             
                foreach ($lines as $line) 
                {
                    $categoria = [
                            'id' => $line->c_id ?? null,
                            'categoria' => $line->categoria ?? null,
                            'descricao' => $line->c_desc ?? null,
                    ];
                    

                    $fornecedor = [
                            'id' => $line->for_id ?? null,
                            'fornecedor' => $line->fornecedor ?? null,
                            'cpf' => $line->cpf ?? null,
                            'telefone' => $line->telefone ?? null,
                            'endereco' => $line->endereco ?? null,
                    ];

                    
                    $categoriaFilter = array_filter($categoria, fn($v) => !is_null($v));
                    $fornecedorFilter = array_filter($fornecedor, fn($v) => !is_null($v));
                    
                    $categoria = empty($categoriaFilter) ? null : $categoriaFilter;
                    $fornecedor = empty($fornecedorFilter) ? null : $fornecedorFilter;
                    

                    $datas[] = [
                        'id' => $line->prod_id ?? null,
                        'categoria_id' => $line->categoria_id ?? null,
                        'fornecedor_id' => $line->fornecedor_id ?? null,
                        'produto' => $line->produto ?? null,
                        'preco' => $line->preco ??null,
                        'quantidade' => $line->quantidade_max ?? null,
                        'quantidade_min' => $line->quantidade_min ?? null,
                        'descricao' => $line->descricao ?? null,
                        'unidade_medida' => $line->unidade_medida ?? null,
                        'categoria' => $categoria,
                        'fornecedor' => $fornecedor,
                        
                    ];
                }

               
            
                for ($i=0; $i < count($datas); $i++) 
                { 
                    $data[] = array_filter($datas[$i], fn($v) => !is_null($v));
                }
                
        
                return $data;  // Retorna o array de objeto
        
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error no banco de dados" . $e->getMessage()); // Lança exceção em caso de erro
            }
                finally 
                {
                    Conexao::closeConexao(); //fechando conexão
                } 
    }
    
    public function entrada_quantidade(int $idProduto, int $quantidadeEntrada, int $quantidadeTotal)
    {
        try
        {
            if(is_numeric($idProduto) && !empty($idProduto))
            {
                $this->quantidade += $quantidadeTotal + $quantidadeEntrada;

                $query = "UPDATE produtos SET quantidade_max=:qt WHERE id = :idProduto";
                $stm = self::$conexao->prepare($query);
                $stm->bindParam(':idProduto', $idProduto, PDO::PARAM_INT);
                $stm->bindParam(':qt', $this->quantidade, PDO::PARAM_INT);
                $stm->execute();

                if($stm)
                {
                   $produto = $this->exibirProdutosId($idProduto);          
                    return $produto['quantidade'];
                }
                
            }

            return false;

        }
            catch(PDOException $error)
            {
                http_response_code(500);
                throw new Exception("Error no banco de dados ". $error->getMessage());
            }
    }
}
