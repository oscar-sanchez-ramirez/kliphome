<?php

namespace App\Jobs;

use DB;
use Mail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OneSignal;
use App\User;
use App\Order;
use App\Address;
use App\Payment;
use App\Notifications\Database\NoFixermanForOrder;
use Illuminate\Support\Facades\Log;
use App\Notifications\Database\NotifyNewOrder as DatabaseNotifyNewOrder;

class NotifyNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $id;
    protected $email;
    /**
     * IN THIS JOB WILL NOTIFY TO FIXERMEN WITH CATEGORIES MATCHED
     *
     * @return void
     */
    public function __construct($id,$email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::where('id',$this->id)->first();
        $municipio = Address::where('id',$order->address)->pluck('municipio');
        $category = $this->table($order->type_service,$order->selected_id);
        $user_match_categories =
        DB::table('users as u')
        ->join('selected_categories as sc','u.id','sc.user_id')
        ->join('selected_delegations as sd','u.id','sd.user_id')
        ->select('u.*')
        ->where('sc.category_id',$category[0]->id)
        ->where('sd.municipio',$municipio)
        ->where('u.state',1)->get();
        if(count($user_match_categories) == 0){
            $admin = User::where('type','ADMINISTRATOR')->first();
            $admin->notify(new NoFixermanForOrder($order));
        }else{
            foreach ($user_match_categories as $key) {
                $user = User::where('id',$key->id)->first();
                $user->mensajeFixerMan = "Tienes una solicitud nueva de trabajo";
                $user->notify(new DatabaseNotifyNewOrder($user));
                $notification = $user->notifications()->first();
                $user->notification_id = $notification->id;
                $user->sendNotification($user->email,'NotifyNewOrder',$user);
            }
        }
        Order::where('id',$this->id)->update([
            'state' => "FIXERMAN_NOTIFIED"
        ]);
        $visita = Payment::where('order_id',$this->id)->where('description',"VISITA")->where('state',1)->first();
        if($visita){
            $fecha = Carbon::createFromFormat('Y/m/d H:i', $order->service_date);

            $usuario = array('visita' => $visita->price,'fecha'=> $fecha->format('d/m/Y H:i'),'service_image'=>$order->service_image);
            $mail = $this->email;
            Mail::send('emails.visitorder',$usuario, function($msj) use ($mail){
                $msj->subject('KlipHome: Tu orden de servicio fue procesado');
                $msj->to($mail,"Detalle");
            });
        }
    }


    private function table($type_service,$id){

        switch ($type_service) {
            case 'Category':
                $category = DB::table('categories')->select('title as service','id','title as category','visit_price')->where('id',$id)->get();
                return $category;
                break;
            case 'SubCategory':
                $category  = DB::table('sub_categories as su')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id','ca.visit_price','su.title as service','ca.title as category','su.title as sub_category','su.title as serviceTrait')->where('su.id',$id)->get();
                return $category;
                break;
            case 'Service':
                $category = DB::table('services as se')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id','ca.visit_price','se.title as service','ca.title as category','su.title as sub_category','se.title as serviceTrait')->where('se.id',$id)->get();
                return $category;
                break;
            case 'SubService':
                $category = DB::table('sub_services as subse')->join('services as se','se.id','subse.service_id')->join('sub_categories as su','se.subcategory_id','su.id')->join('categories as ca','su.category_id','ca.id')->select('ca.title','ca.id','ca.visit_price','subse.title as service','ca.title as category','su.title as sub_category','se.title as serviceTrait')->where('subse.id',$id)->get();
                return $category;
                break;

            default:
                # code...
                break;
        }

    }
}
