<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    public function validate(Request $request , array $rules)
    {
        $validated = Validator::make($request->all(),$rules);

        if( $validated->fails() )
            throw new ValidationException( $validated );
    }
}