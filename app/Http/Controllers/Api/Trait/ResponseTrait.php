<?php

namespace App\Http\Controllers\Api\Trait;

trait ResponseTrait
{

    public function apiSuccess($data)
    {
        return [
            'code' => 0,
            'data' => $data,
            'message' => 'success'
        ];
    }

    public function apiError( $code = 1,$message = 'error' , $data = null )
    {
        return [
            'code' => $code,
            'data' => $data,
            'message' => $message
        ];
    }
}
