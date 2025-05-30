<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    public function successResponse($data , $code = Response::HTTP_OK)
    {
        return response()->json($data,$code);
    }

    public function errorResponse($message,$code)
    {
        return response()->json(["error"=>$message,"code"=>$code]);
    }

    public function exceptionResponse($message,$code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json(['error'=>$message],$code);
    }
}