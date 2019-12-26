<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\AproveFixerMan;
use App\Notifications\NotifyNewOrder;
use App\Notifications\OneSignal\ApproveOrderFixerman;
use App\Notifications\OneSignal\DisapproveOrderFixerman;
use Illuminate\Support\Facades\Log;


class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','lastName','phone','type','state',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'created_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function children(){
        return $this->hasMany(Address::class, 'user_id')->orderBy('created_at', 'asc');
    }
    public function sendNotification($email,$type)
    {
        $this->email = $email;
        switch ($type) {
            case 'AproveFixerMan':
                //Notify when a FixerMan is approved
                $this->notify(new AproveFixerMan($this));
                break;
            case 'ApproveOrderFixerMan':
                //Notify when a Fixerman Request was approved
                $this->notify(new ApproveOrderFixerman($this));
                # code...
                break;
            case 'DisapproveOrderFixerMan':
                //Notify when a Fixerman Request was disapproved
                $this->notify(new DisapproveOrderFixerman($this));
                break;
            case 'sendNotificationOrderMatch':
                //Notify when user create a order and exists fixerman with the same category
                $this->notify(new NotifyNewOrder($this));
                break;
            default:
                # code...
                break;
        }

    }

    public function routeNotificationForOneSignal()
    {
        Log::notice("one");
        Log::notice($this->email);
        return ['tags' => ['key' => 'email', 'relation' => '=', 'value' => $this->email]];
    }
}
