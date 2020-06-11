<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends ControllerWeb
{
    public function terminos(){
        return view('terminos');
    }
}
