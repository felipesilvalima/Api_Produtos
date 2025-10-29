<?php declare(strict_types=1); 

namespace app\model;
use app\model\Conexao;
use Exception;
use Firebase\JWT\JWT;
use PDO;
use PDOException;

require_once __DIR__. '/../../config/env.php';
class AuthModel
{
    
    private static $conexao;
    private string  $email;
    private string $password;

    public function __construct()
    {
        self::$conexao = new Conexao();
    }

    public function Autentication(array $credencias)
    {
        try 
        {
            session_start(); // inciando sessão

            //atributos recebendo as credencias
            $this->email = $credencias['email'] ?? null; 
            $this->password = $credencias['senha'] ?? null;

            $sql = "SELECT * FROM user WHERE email = :email"; // sql
            $stm = self::$conexao->Conexao()->prepare($sql); // preparando sql
            $stm->bindParam(':email',$this->email, PDO::PARAM_STR); //passando pârametro
            $stm->execute(); // executando sql
    
                if($stm->rowCount() > 0) // se existir o email no banco
                {
                    $data = $stm->fetchObject(); // pegando as credencias do  banco

                    if(password_verify($this->password,$data->password)) // verificando se a senha está correta
                    {
                        $_SESSION['Autenticado']; // criando sessao
                        return $data;
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
        
    }

    public static function generateToken(object $user)
    {
        try 
        { 

           // Dados do usuário autenticado
            $idUser = $user->id; 
            $name = $user->name;

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
               http_response_code(500);
               echo "Erro ao gerar token:" . $e->getMessage();
            }
    }


}