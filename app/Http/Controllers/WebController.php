<?php

namespace App\Http\Controllers;

use Nexmo;
use App\User;
use Carbon\Carbon;
use App\AdminCoupon;
use App\TempPayment;
use Illuminate\Http\Request;

class WebController extends ControllerWeb
{
    public function terminos(){
        return view('terminos');
    }
    public function email_verified($code){
        $user = User::where('code',$code)->where('email_verified_at',null)->first();
        if($user){
            User::where('code',$code)->update([
                'email_verified_at' => Carbon::now()
            ]);
            return view('emailverified');
        }else{
            return view('emailnotverified');
        }
    }
    public function test(){
        Nexmo::message()->send([
            'to'   => '+51997491844',
            'from' => 'Fisioterapia de altura',
            'text' => 'Curso FISIOTERAPIA EN NEUROREHABILITACIÓN: https://zoom.us/j/96180095946?pwd=aDJSVzlJRGZOUXVpQVZpMkN4bEFrUT09 Acceso: 175342',
            'type' => 'text'
        ]);
        return "123";
        // $array = [["code"=>'2UK7AV',"discount" =>	50],
        // ["code"=>'34ZMLM',"discount" =>	50],
        // ["code"=>'24CR9K',"discount" =>	50],
        // ["code"=>'94VFYY',"discount" =>	50],
        // ["code"=>'LT0ZXC',"discount" =>	50],
        // ["code"=>'ZPN5MS',"discount" =>	50],
        // ["code"=>'LX6JSN',"discount" =>	50],
        // ["code"=>'3AK667',"discount" =>	50],
        // ["code"=>'NWCGU1',"discount" =>	50],
        // ["code"=>'NCX4OK',"discount" =>	50],
        // ["code"=>'BDTAJN',"discount" =>	50],
        // ["code"=>'2T6F6U',"discount" =>	50],
        // ["code"=>'J62AWH',"discount" =>	50],
        // ["code"=>'SOZFND',"discount" =>	50],
        // ["code"=>'5I9KB4',"discount" =>	50],
        // ["code"=>'2GLAZ0',"discount" =>	50],
        // ["code"=>'QD4EBT',"discount" =>	50],
        // ["code"=>'ASBFCC',"discount" =>	50],
        // ["code"=>'VRMBH9',"discount" =>	50],
        // ["code"=>'KT98CK',"discount" =>	50],
        // ["code"=>'XFXROA',"discount" =>	100],
        // ["code"=>'LE00ZC',"discount" =>	100],
        // ["code"=>'AGRHSN',"discount" =>	100],
        // ["code"=>'HZ8CNP',"discount" =>	100],
        // ["code"=>'119SWH',"discount" =>	100],
        // ["code"=>'LPO6XR',"discount" =>	100],
        // ["code"=>'3JTBVI',"discount" =>	100],
        // ["code"=>'CVZ0KC',"discount" =>	100],
        // ["code"=>'3XVGXW',"discount" =>	100],
        // ["code"=>'LZEB22',"discount" =>	100],
        // ["code"=>'CTOJDA',"discount" =>	100],
        // ["code"=>'4EC816',"discount" =>	100],
        // ["code"=>'MO3NKF',"discount" =>	100],
        // ["code"=>'IEESEX',"discount" =>	100],
        // ["code"=>'WA73LZ',"discount" =>	100],
        // ["code"=>'F23II0',"discount" =>	100],
        // ["code"=>'T3Q2ZK',"discount" =>	100],
        // ["code"=>'P3MZM9',"discount" =>	100],
        // ["code"=>'ZRZF89',"discount" =>	100],
        // ["code"=>'GH4ZC8',"discount" =>	100],
        // ["code"=>'9TUZVV',"discount" =>	250],
        // ["code"=>'DBA7LZ',"discount" =>	250],
        // ["code"=>'ZVOL3D',"discount" =>	250],
        // ["code"=>'WJYSUQ',"discount" =>	250],
        // ["code"=>'KHC4FK',"discount" =>	250],
        // ["code"=>'8R04C1',"discount" =>	250],
        // ["code"=>'GCEHNT',"discount" =>	250],
        // ["code"=>'S2ZMRR',"discount" =>	250],
        // ["code"=>'OGNTVW',"discount" =>	250],
        // ["code"=>'01KSIM',"discount" =>	250],
        // ["code"=>'CTNMCD',"discount" =>	250],
        // ["code"=>'CYZE0T',"discount" =>	250],
        // ["code"=>'TFTAW4',"discount" =>	250],
        // ["code"=>'T1FA47',"discount" =>	250],
        // ["code"=>'ZTI92N',"discount" =>	250],
        // ["code"=>'WCIMMZ',"discount" =>	250],
        // ["code"=>'RLPB58',"discount" =>	250],
        // ["code"=>'UGO31W',"discount" =>	250],
        // ["code"=>'W0SELJ',"discount" =>	250],
        // ["code"=>'58IL36',"discount" =>	250],
        // ["code"=>'KDHOO2',"discount" =>	500],
        // ["code"=>'YCK2II',"discount" =>	500],
        // ["code"=>'YD0SE5',"discount" =>	500],
        // ["code"=>'7RX2A7',"discount" =>	500],
        // ["code"=>'EMDO51',"discount" =>	500],
        // ["code"=>'VARSE9',"discount" =>	500],
        // ["code"=>'9V1KJE',"discount" =>	500],
        // ["code"=>'3P72XP',"discount" =>	500],
        // ["code"=>'6614YC',"discount" =>	500],
        // ["code"=>'A40DB6',"discount" =>	500],
        // ["code"=>'8WAK8W',"discount" =>	500],
        // ["code"=>'URJ1Y1',"discount" =>	500],
        // ["code"=>'GQL5LZ',"discount" =>	500],
        // ["code"=>'O8G6CI',"discount" =>	500],
        // ["code"=>'SJBPDY',"discount" =>	500],
        // ["code"=>'OY24X2',"discount" =>	500],
        // ["code"=>'RDW92O',"discount" =>	500],
        // ["code"=>'ZSZA9Q',"discount" =>	500],
        // ["code"=>'4CXIG8',"discount" =>	500],
        // ["code"=>'ST76FH',"discount" =>	500],
        // ["code"=>'CKHYI4',"discount" =>	1000],
        // ["code"=>'YZNBT6',"discount" =>	1000],
        // ["code"=>'1L9C9O',"discount" =>	1000],
        // ["code"=>'LM31LU',"discount" =>	1000],
        // ["code"=>'Z8DJ89',"discount" =>	1000],
        // ["code"=>'GROWR6',"discount" =>	1000],
        // ["code"=>'7SPG7P',"discount" =>	1000],
        // ["code"=>'G88ARO',"discount" =>	1000],
        // ["code"=>'O6CGZO',"discount" =>	1000],
        // ["code"=>'2AI6XE',"discount" =>	1000],
        // ["code"=>'54SKD5',"discount" =>	1000],
        // ["code"=>'474JXO',"discount" =>	1000],
        // ["code"=>'I8MNCL',"discount" =>	1000],
        // ["code"=>'KR5XWI',"discount" =>	1000],
        // ["code"=>'3YSC2N',"discount" =>	1000],
        // ["code"=>'781E1C',"discount" =>	1000],
        // ["code"=>'1G3FVE',"discount" =>	1000],
        // ["code"=>'UYRP8W',"discount" =>	1000],
        // ["code"=>'VBJCHV',"discount" =>	1000]];

        // for ($i=0; $i < count($array); $i++) {
        //     $new_coupon = new AdminCoupon;
        //     $new_coupon->code = $array[$i]["code"];
        //     $new_coupon->discount = $array[$i]["discount"];
        //     $new_coupon->state = 1;
        //     $new_coupon->type = 'Pesos';
        //     $new_coupon->responsable = 1052;
        //     $new_coupon->save();
        // }
        // sleep(1);
        // $revisar_pagos_previos = TempPayment::where('user_id',733)->where('price',"50")->first();
        // if($revisar_pagos_previos){
        //     return response()->json([
        //         'success' => true,
        //         'message' => "Pago exitoso",
        //     ]);
        // }else{
        //     return 2;
        // }
        $array = ["962590283",
        "941299615",
        "997491844",
        "955458595",
        "983370485",
        "929631914",
        "943809315",
        "945299871",
        "954818270",
        "982763856",
        "914262912",
        "926737221",
        "950301013",
        "987533375",
        "948639860",
        "980589075",
        "951153990",
        "957714513",
        "936978574",
        "964151387",
        "983268856",
        "975272575",
        "993489680",
        "994873393",
        "974386461",
        "980913813",
        "953177340",
        "932194868",
        "978641641",
        "991475905",
        "943896797",
        "982047049",
        "993774884",
        "999347858",
        "997297755",
        "997297755",
        "997297755",
        "987155922",
        "914621985",
        "944538720",
        "991272780",
        "949558501",
        "986115537",
        "983084178",
        "955796968",
        "968343969",
        "999524524",
        "999524524",
        "999524524",
        "964951280",
        "988479177",
        "954815572",
        "973562462",
        "915058230",
        "955796968",
        "950305184",
        "997644250",
        "956358226",
        "928615281",
        "960556747",
        "902986457",
        "984748799",
        "947730446",
        "954015539",
        "993950166",
        "923375785",
        "965208052",
        "973459367",
        "923375785",
        "968751288",
        "986035633",
        "958721356",
        "917878512",
        "955449768",
        "955449768",
        "959319711",
        "945880537",
        "959241891",
        "951796639",
        "959319711",
        "945880537",
        "959241891",
        "951216110",
        "920033304",
        "964298607",
        "910877521"];
        for ($i=0; $i < count($array); $i++) {
            Nexmo::message()->send([
                'to'   => '+51'.$array[$i],
                'from' => 'Fisioterapia de altura',
                'text' => 'Curso FISIOTERAPIA EN NEUROREHABILITACIÓN: https://zoom.us/j/96180095946?pwd=aDJSVzlJRGZOUXVpQVZpMkN4bEFrUT09 Acceso: 175342',
                'type' => 'text'
            ]);
        }
        return "si";
    }
}
