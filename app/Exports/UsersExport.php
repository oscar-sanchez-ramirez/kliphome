<?php

namespace App\Exports;

use App\User;
use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;

class UsersExport implements FromCollection
{
    protected $key;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($key)
    {
        $this->key = $key;

    }

    public function collection()
    {
        switch ($this->key) {
            case 'con_orden':
                $usuarios = Order::pluck('user_id');
                return User::where('type','AppUser')->whereIn('id',$usuarios)->orderBy('id',"DESC")->get(['name','lastName','phone','email']);
            case 'sin_orden':
                $usuarios = Order::pluck('user_id');
                return User::where('type','AppUser')->whereNotIn('id',$usuarios)->orderBy('id',"DESC")->get(['name','lastName','phone','email']);
            case 'todos':
                return User::where('type','AppUser')->orderBy('id',"DESC")->get(['name','lastName','phone','email']);
            default:
                # code...
                break;
        }
    }
}
