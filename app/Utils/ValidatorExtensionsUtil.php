<?php 

namespace App\Utils;

use Validator;

class ValidatorExtensionsUtil 
{
	public static function boot() 
	{
        Validator::extend('dateBeforeOrEqual', function($attribute, $value, $parameters) {
            try{
                return strtotime($value) <= strtotime($parameters[0]);
            } catch(\Exception $e){
                return false;
            }
        });

        Validator::extend('dateAfterOrEqual', function($attribute, $value, $parameters) {
            try{
                return strtotime($value) >= strtotime($parameters[0]);
            } catch(\Exception $e){
                return false;
            }
        });

        Validator::extend('remote_file_exists',function($attribute,$value,$parameters){
            if(isset($parameters[1]) && $parameters[1] == 0){
                return true;
            }
            if(isset($parameters[0]) && !empty($parameters[0])){
                $integrationType = IntegrationType::find($parameters[0]);
                if(isset($parameters[2]) && !empty($parameters[2])){
                    $extraConfig = $parameters[2];
                } else {
                    $extraConfig = null;
                }
                return Utils::remoteFileExists(IntegrationUtils::buildUrlPath($value,$integrationType->layout->system), $extraConfig);
            }
            return false;
        });

        Validator::extend('cpf',function($attribute,$value,$parameters){
            $validateCpf = function ($data, $apenasNumeros = false) {
                // Testar o formato da string
                if ($apenasNumeros) {
                    if (!ctype_digit($data) || strlen($data) != 11) {
                        return false;
                    }
                    $numeros = $data;
                } else {
                    if (!preg_match('/\d{3}\.\d{3}\.\d{3}-\d{2}/', $data)) {
                        return false;
                    }
                    $numeros = substr($data, 0, 3) . substr($data, 4, 3) . substr($data, 8, 3) . substr($data, 12, 2);
                }
                // Testar se todos os n�meros est�o iguais
                for ($i = 0; $i <= 9; $i++) {
                    if (str_repeat($i, 11) == $numeros) {
                        return false;
                    }
                }
                // Testar o digito verificador
                $dv = substr($numeros, -2);
                for ($pos = 9; $pos <= 10; $pos++) {
                    $soma = 0;
                    $posicao = $pos + 1;
                    for ($i = 0; $i <= $pos - 1; $i++, $posicao--) {
                        $soma += $numeros{$i} * $posicao;
                    }
                    $div = $soma % 11;
                    if ($div < 2) {
                        $numeros{$pos} = 0;
                    } else {
                        $numeros{$pos} = 11 - $div;
                    }
                }
                $dvCorreto = $numeros{9} * 10 + $numeros{10};
                return $dvCorreto == $dv;
            };
            $cpf = preg_replace('/\D/', '', $value);
            return $validateCpf($cpf,true);
        });

        Validator::extend('cnpj',function($attribute,$value,$parameters){
            $validaCnpj = function($data, $apenasNumeros = false) {
                // Testar o formato da strings
                if ($apenasNumeros) {
                    if (!ctype_digit($data) || strlen($data) != 14) {
                        return false;
                    }
                    $numeros = $data;
                } else {
                    if (!preg_match('/\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}/', $data)) {
                        return false;
                    }
                    $numeros = substr($data, 0, 2) . substr($data, 3, 3) . substr($data, 7, 3) . substr($data, 11, 4) . substr($data, 16, 2);
                }
                // Testar o digito verificador
                for ($pos = 12; $pos <= 13; $pos++) {
                    $soma = 0;
                    $mult = $pos - 7; // 5 ou 6
                    for ($i = 0; $i < $pos; $i++) {
                        $soma += $numeros{$i} * $mult--;
                        if ($mult === 1) {
                            $mult = 9;
                        }
                    }
                    $div = $soma % 11;
                    if ($div < 2) {
                        $dvCorreto = 0;
                    } else {
                        $dvCorreto = 11 - $div;
                    }
                    if ($dvCorreto != $numeros{$pos}) {
                        return false;
                    }
                }
                return true;
            };
            $cnpj = preg_replace('/\D/', '', $value);
            if(empty(intval($cnpj))){
                return false;
            }
            return $validaCnpj($cnpj,true);
        });

        Validator::extend('dimension_min', function($attribute, $value, $parameters) {
            if(function_exists('getimagesize'))
            {
                $image_info = getimagesize($value);
                $image_width = $image_info[0];
                $image_height = $image_info[1];
                if( (isset($parameters[0]) && $parameters[0] != 0) && $image_width < $parameters[0]) return false;
                if( (isset($parameters[1]) && $parameters[1] != 0) && $image_height < $parameters[1] ) return false;
                return true;
            }
        });

        Validator::extend('dimension_max', function($attribute, $value, $parameters) {
            if(function_exists('getimagesize'))
            {
                $image_info = getimagesize($value);
                $image_width = $image_info[0];
                $image_height = $image_info[1];
                if( (isset($parameters[0]) && $parameters[0] != 0) && $image_width > $parameters[0]) return false;
                if( (isset($parameters[1]) && $parameters[1] != 0) && $image_height > $parameters[1] ) return false;
                return true;
            }
        });

        Validator::extend('cep', function($attribute, $value) {
            return preg_match('/^\d{2}\.?\d{3}-?\d{3}$/', $value) > 0;
        });

        Validator::extend('full_name', function($attribute, $value, $parameters) {
            $names = explode(" ",trim($value));
            return count($names) > 1;
        });
	}
}