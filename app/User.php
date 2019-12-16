<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\AproveFixerMan;
use App\Notifications\NotifyNewOrder;
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
        'password', 'remember_token',
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
    public function sendNotification($email)
    {
        $this->email = $email;
        $this->notify(new AproveFixerMan($this)); //Pass the model data to the OneSignal Notificator
    }

    public function sendNotificationOrderMatch($email)
    {
        $this->email = $email;
        $this->notify(new NotifyNewOrder($this)); //Pass the model data to the OneSignal Notificator
    }

    public function routeNotificationForOneSignal()
    {
        Log::notice("one");
        Log::notice($this->email);
        return ['tags' => ['key' => 'email', 'relation' => '=', 'value' => $this->email]];
    }
}
