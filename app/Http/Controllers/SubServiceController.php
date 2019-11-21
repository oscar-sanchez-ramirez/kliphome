<?php

namespace App\Http\Controllers;

use App\SubService;
use Illuminate\Http\Request;

class SubServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request)
    {
        SubService::where('id',$request->subservice_id)->update([
            'title' => $request->subservice_title
        ]);
    }

    public function nuevo(Request $request)
    {
        $sub = new SubService();
        $sub->service_id = $request->service_id;
        $sub->title = $request->subservice_title;
        $sub->save();
    }

    public function eliminar(Request $request){
        SubService::where('id',$request->subservice_id)->delete();
    }

    public function getSubservice($id){
        $sub_services = SubService::where('service_id',$id)->get();
        $data = '';
        foreach($sub_services as $sub){
            $data = $data.'<tr>
                <td>'.$sub->SubServiceName($id)["title"].'</td>
                <td  class="td_'.$sub->id.'">'.$sub->title.'</td>
                <td> <div class="table-data-feature">
                <button class="item" data-toggle="tooltip" data-placement="top" title="Edit" onclick="setUpdateField('.(str_replace('"','\'',json_encode(array("id" => $sub->id, "title" => $sub->title)))).')">
                    <i class="zmdi zmdi-edit"></i>
                </button>
                <button class="item" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteSubService('.$sub->id.')">
                    <i class="zmdi zmdi-delete"></i>
                </button>
                </div></td>
            </tr>';
        }
        return $data;
    }
}
