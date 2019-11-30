<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SelectedCategories;
use App\SelectedDelegation;
use App\User;

class FixerManController extends Controller
{
    public function index(){
        $users = User::where('type','AppFixerMan')->get();
        return view('admin.fixerman.index')->with('users',$users);
    }
    public function detail($id){
        $delegation = SelectedDelegation::where('user_id',$id)->with('parent')->get(['id','delegation_id']);
        $categories = SelectedCategories::where('user_id',$id)->with('parent')->get(['id','category_id']);
        return response()->json([
            'delegations' => $delegation,
            'categories' => $categories
        ]);
    }
}
