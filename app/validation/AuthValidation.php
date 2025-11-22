<?php declare(strict_types=1); 

namespace app\validation;

class AuthValidation
{
    public static function validationAllData($credencias)
    {
        $messages = [];

            $messages["email"] = self::email($credencias['email'] ?? null); // recebendo o retorno das menssagens
            $messages["senha"] = self::password($credencias['senha'] ?? null);
                   
                $responses[] = array_filter($messages, fn($m) => !is_null($m)); // return apenas arrays que não são nulos
            
                    if(!empty($responses)) // se as resposta não for vázia, retornar resposta, se não retornar null
                    {
                        return $responses;
                    }                    
    }



    private static function email($email)
    {

        if(empty($email))
        {
            $messages[] = "Campo precisar ser prenchido";
        }
            elseif(!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                 $messages[] = "Formato inválido";
            }
        
        return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null
    }

    private static function password($password)
    {
        if(empty($password))
        {
            $messages[] = "Campo precisar ser prenchido";
        }

        return !empty($messages) && isset($messages) ? $messages: null; // se não for vazio e existir retornar menssagem se não null
    }
}