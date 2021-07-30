<?php

namespace App\Http\Resources;

use App\Service;
// use App\SubService;
// use DB;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return Service::where('subcategory_id',$this->id)->select('id','title','subcategory_id')->with('children')->get();
    }
}
