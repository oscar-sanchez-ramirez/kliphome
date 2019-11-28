<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FixerManController extends Controller
{
    public function register(Request $request){
        return $request->all();
    }
}
