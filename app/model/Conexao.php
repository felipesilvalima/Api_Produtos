<?php declare(strict_types=1); 

namespace model\Conexao;

use Exception;
use PDO;
use PDOException;

class Conexao
{
     public static function Conexao()
    { 
        try 
        {
            $conexao = null;

            $conexao = new PDO("mysql: host=localhost; dbname=controler_de_estoque","root","");
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                return $conexao;
                 
        } 
            catch (PDOException $e) 
            {
                throw new Exception("error". $e->getMessage());
            }
    }
}