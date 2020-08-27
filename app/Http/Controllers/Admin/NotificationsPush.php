<?php

namespace App\Http\Controllers\Admin;

use App\NotificationsPush as NotificationsPushModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Admin\NoticationPush as JobNoticationPush;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class NotificationsPush extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificaciones = NotificationsPushModel::paginate(10);
        return view('admin.notificationspush.index',compact('notificaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.notificationspush.nuevo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $nueva = new NotificationsPushModel;
        $nueva->message = $request->title;
        $nueva->audience = $request->clientes.' '.$request->tecnicos;
        $nueva->save();
        dispatch(new JobNoticationPush($request->clientes,$request->tecnicos,$request->title));
        return Redirect::action('Admin\NotificationsPush@index');
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
    public function update(Request $request, $id)
    {
        //
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
}
