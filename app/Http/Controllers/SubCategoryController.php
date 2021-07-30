<?php

namespace App\Http\Controllers;

use App\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
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
        return view('admin.categories.index')->with('categories',$categories);
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

    }
    public function nuevo(Request $request)
    {
        $sub = new SubCategory();
        $sub->category_id = $request->category_id;
        $sub->title = $request->subcategory_title;
        $sub->save();
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
        SubCategory::where('id',$request->subcategory_id)->update([
            'title' => $request->subcategory_title
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request)
    {
        SubCategory::where('id',$request->subcategory_id)->delete();
    }

    public function getSubcategory($id){
        $sub_categories = SubCategory::where('category_id',$id)->get();
        $data = '';
        foreach($sub_categories as $sub){
            $data = $data.'<tr>
                <td>'.$sub->CategoryName($id)["title"].'</td>
                <td  class="td_'.$sub->id.'">'.$sub->title.'</td>
                <td> <div class="table-data-feature">
                <button class="item" data-toggle="tooltip" data-placement="top" title="Edit" onclick="setUpdateField('.(str_replace('"','\'',json_encode(array("id" => $sub->id, "title" => $sub->title)))).')">
                    <i class="zmdi zmdi-edit"></i>
                </button>
                <button class="item" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteSubCategory('.$sub->id.')">
                    <i class="zmdi zmdi-delete"></i>
                </button>
                </div></td>
            </tr>';
        }
        return $data;
    }
}
