<?php

namespace App\Exceptions;
use Exception;

class NotFound extends Exception
{
    public function render()
    {
        $responseJson['statusCode']=404;
        $responseJson['message']='This request not found';
        return response($responseJson,404);
    }
}
