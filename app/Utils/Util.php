<?php

 namespace App\Utils;

 class Util
 {
    public static function coalesce(){
        $args = func_get_args();
        foreach($args as $arg){
            if(!empty($arg)){
                return $arg;
            }
        }
        return null;
    }
    
    public static function getExceptionFullMessage(\Exception $e){
      $message = $e->getMessage();
      $message .= ". File: ".$e->getFile();
      $message .= ". Line: ".$e->getLine();
      return $message;
    }
 }