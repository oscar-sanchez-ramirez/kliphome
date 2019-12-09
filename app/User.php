<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\AproveFixerMan;


class User extends Authenticatable
{
    use Notifiable,HasApiTokens;
    public function __construct(
        User $user
    ) {
        $this->user = $user;
    }
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
    public function sendNotification()
    {
        $this->notify(new AproveFixerMan($this)); //Pass the model data to the OneSignal Notificator
    }
    public function routeNotificationForOneSignal()
    {
        /*
         * you have to return the one signal player id tat will
         * receive the message of if you want you can return
         * an array of players id
         */
        return ['tags' => ['key' => 'email', 'relation' => '=', 'value' => $this->user->email]];
    }
}
