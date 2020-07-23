<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Log;
use DB;
use App\Notifications\FixerMan\OneDayLeftNotification;
use App\Notifications\FixerMan\FourHoursLeftNotification;
use App\Notifications\FixerMan\ApproveFixerMan;
use App\Notifications\FixerMan\DisapproveOrderFixerman;
use App\Notifications\FixerMan\ApproveOrderFixerMan;
use App\Notifications\FixerMan\NotifyNewOrder;
use App\Notifications\FixerMan\ServiceQualified;
use App\Notifications\FixerMan\NewMessageNotification;
use App\Notifications\FixerMan\OrderCancelled;

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
        'name', 'email', 'password','lastName','phone','type','state','code','provider'
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
    public function sendNotification($email,$type,$data)
    {
        $this->email = $email;
        switch ($type) {
            case 'OneDayLeftNotification':
                //Notify when a FixerMan is approved
                $this->notify(new OneDayLeftNotification($data));
                break;
            case 'FourHoursLeftNotification':
                //Notify when a FixerMan is approved
                $this->notify(new FourHoursLeftNotification($data));
                break;
            case 'ApproveFixerMan':
                //Notify when a FixerMan is approved
                $this->notify(new ApproveFixerMan($data));
                break;
            case 'ApproveOrderFixerMan':
                //Notify when a Fixerman Request was approved
                $this->notify(new ApproveOrderFixerman($data));
                # code...
                break;
            case 'DisapproveOrderFixerMan':
                //Notify when a Fixerman Request was disapproved
                $this->notify(new DisapproveOrderFixerman($data));
                break;
            case 'NotifyNewOrder':
                //Notify when user create a order and exists fixerman with the same category
                $this->notify(new NotifyNewOrder($data));
                break;
            case 'ServiceQualified':
                // Notify when user create a order and exists fixerman with the same category
                $this->notify(new ServiceQualified($data));
                break;
            case 'NewMessageNotification':
                $this->notify(new NewMessageNotification($data));
                break;
            case 'OrderCancelled':
                $this->notify(new OrderCancelled($data));
                break;
            default:
                # code...
                break;
        }

    }

    public function routeNotificationForOneSignal()
    {
        return ['tags' => ['key' => 'email', 'relation' => '=', 'value' => $this->email]];
    }

    public function categories(){
        return $this->hasMany(SelectedCategories::class, 'user_id')->orderBy('created_at', 'asc');
    }

    public function stats(){
        return $this->hasOne(FixermanStat::class);
    }
}
