<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class FixerManController extends ApiController
{
    public function register(Request $request){
        return $request->all();
    }
}
