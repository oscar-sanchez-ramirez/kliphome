<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigSystemController extends Controller
{
    //
    public function revisar_pago(){
        return ConfigSystem::payment;
    }
}
