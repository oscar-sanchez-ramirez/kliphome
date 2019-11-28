<?php

namespace App\Http\Controllers\ApiRest;

use App\Delegation;
use App\Http\Controllers\ApiController;
// use Illuminate\Http\Request;

class DelegationController extends ApiController
{
    //
    public function index(){
        $delegations = Delegation::where('state',true)->select('id','title')->get();
        return Response(json_encode(array('delegations' => $delegations)));
    }
}
