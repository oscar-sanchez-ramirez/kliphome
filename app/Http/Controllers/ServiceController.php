<?php

namespace App\Http\Controllers;

use App\Service;
use App\SubCategory;
use App\Category;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ServiceController extends Controller
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
        $categories = Category::all();
        $services = new Service();
        return view('admin.services.index')->with('services',$services->allServices())->with('categories',$categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = SubCategory::all();
        return view('admin.services.create')->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_service = new Service;
        $new_service->title = $request->title;
        $new_service->subcategory_id = $request->category_id;
        $new_service->save();
        return Redirect::action('ServiceController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = SubCategory::all();
        $service = Service::where('id',$id)->first();
        return view('admin.services.edit')->with('service',$service)->with('categories',$categories);
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
    public function update(Request $request, $id)
    {
        Service::where('id',$id)->update([
            'title' => $request->title,
            'subcategory_id' => $request->category_id
        ]);
        return Redirect::action('ServiceController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getServicesByCategory($category_id){
        $services = new Service();
        $data = '';
        if($category_id != "all"){
            $array = $services->servicesByCategory($category_id);
        }else{
            $array = $services->allServices();
        }
        foreach( $array as $service){
            $data = $data.'<tr class="tr-shadow">
            <td>
                <label class="au-checkbox">
                    <input type="checkbox">
                    <span class="au-checkmark"></span>
                </label>
            </td>
            <td>'. $service->category.'</td>
            <td>'. $service->subcategory.'</td>
            <td><b>'.$service->title.'</b></td>
            <td>'.\Carbon\Carbon::parse($service->created_at)->diffForHumans() .'</td>
            <td>
                <div class="table-data-feature">
                    <form action="'.url('').'/servicios/'.$service->id.'" type="GET">
                        <button class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i class="zmdi zmdi-edit"></i>
                        </button>
                    </form>
                    <button class="item" data-toggle="modal" data-target="#mediumModal" id="SubServiceModal" data-title="'.$service->title.'" data-id="'.$service->id.'">
                        <i data-toggle="tooltip" data-placement="top" title="SubServicios" class="zmdi zmdi-collection-item-3"></i>
                    </button>
                </div>
            </td>
        </tr>
        <tr class="spacer"></tr>';
        }
        return $data;
    }
}
