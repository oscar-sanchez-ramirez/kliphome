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

    public function postal_code($id){
        $json = json_decode(file_get_contents('http://sepomex.789.mx/'.$id), true);
        return $json;
    }

    public function webhook_oxxo(Request $request){
        Log::notice($request->all());
    }
}
