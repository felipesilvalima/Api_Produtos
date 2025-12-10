<?php declare(strict_types=1); 

namespace app\model;
use Exception;
use Firebase\JWT\JWT;
use PDO;
use PDOException;

class AuthModel
{
    
    private static $conexao;
    private string  $email;
    private string $password;

    public function __construct($db)
    {
        self::$conexao = $db;
    }

    public function Autentication($credencias)
    {
        try 
        {
            //atributos recebendo as credencias
            $this->email = $credencias['email'] ?? null; 
            $this->password = $credencias['senha'] ?? null;

            $sql = "SELECT * FROM user WHERE email = :email"; // sql
            $stm = self::$conexao->prepare($sql); // preparando sql
            $stm->bindParam(':email',$this->email, PDO::PARAM_STR); //passando pârametro
            $stm->execute(); // executando sql
    
                if($stm->rowCount() > 0) // se existir o email no banco
                {
                    $data = $stm->fetchObject(); // pegando as credencias do  banco

                    if(password_verify($this->password,$data->password)) // verificando se a senha está correta
                    {
                        $datas = [
                            "id" => $data->id,
                            "nome" => $data->name,
                            "email" => $data->email
                        ];

                        return $datas;
                    }
                        else // senha inválida
                        {
                            return false;
                        }
            

                }
                    else // usuario inválido
                    {
                        return false;
                    }
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error no banco de dados" . $e->getMessage());
            }
                finally
                {
                    Conexao::closeConexao(); // fechar conexao
                }
        
    }

    public static function generateToken(array $user)
    {
        try 
        { 
           
           // Dados do usuário autenticado
            $idUser = $user['id']; 
            $name = $user['nome'];

             // Definir payload
                $payload = [
                    'uid' => $idUser,
                    'nome' => $name,
                    'iat' => time(),            // hora de emissão
                    'exp' => time() + 3600      // expira em 1 hora
                ];   

             return JWT::encode($payload,  $_ENV['API_KEY'], 'HS256');  // Gerar token JWT (HMAC SHA256)

        } 
            catch (PDOException $e) // error ao gerar token 
            {
               throw new Exception("Erro ao gerar token:" . $e->getMessage());
            }
    }

    public function BuscarUsuario(int $id)
    {
        try
        {

            $sql = "SELECT id, name, email FROM user WHERE id = :id";
      
              $stm = self::$conexao->prepare($sql);
              $stm->bindParam(':id', $id, PDO::PARAM_INT);
              $stm->execute();
    
              $usuario = $stm->fetch(PDO::FETCH_OBJ); 
            
                return $usuario;
             
        }
            catch(PDOException $e)
            {
                throw new Exception("error no banco de dados (BuscarUsuario) " . $e->getMessage());
            }
                finally
                {
                    Conexao::closeConexao();
                }
            
    }


    public static function BlackList(string $token)
    {
        try
        {

            $sql = "INSERT INTO blacklisted_tokens(token) VALUES (:token)";
      
              $stm = self::$conexao->prepare($sql);
              $stm->bindParam(':token', $token, PDO::PARAM_STR);
              $stm->execute();
              
                if($stm)
                {
                    return true;
                }
                
                    return false;
             
        }
            catch(PDOException $e)
            {
                throw new Exception("error no banco de dados (BlackList) " . $e->getMessage());
            }
                finally
                {
                    Conexao::closeConexao();
                }
    }

    public static function VerifyToken_blackList(string $token)
    {
        try
        {

            $sql = "SELECT token FROM blacklisted_tokens WHERE token = :token";
      
              $stm = self::$conexao->prepare($sql);
              $stm->bindParam(':token', $token, PDO::PARAM_STR);
              $stm->execute();
              
                if($stm->rowCount() > 0)
                {
                    return true;
                }

                    return false;
             
        }
            catch(PDOException $e)
            {
                throw new Exception("error no banco de dados (VerifyToken_blackList) " . $e->getMessage());
            }
                finally
                {
                    Conexao::closeConexao();
                }
    }
}