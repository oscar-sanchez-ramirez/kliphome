<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ConfigSystemController extends ApiController
{
    //
    public function revisar_pago(){
        return ConfigSystem::payment;
    }
}
