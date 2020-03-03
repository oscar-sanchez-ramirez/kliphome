<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    use ApiResponser;
    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    //

    public function webhook_oxxo(Request $request){
        Log::notice($request->all());
    }
}
