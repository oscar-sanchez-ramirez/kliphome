<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ConfigSystem extends Model
{
    const socialmedia = [
        'success' => true,
        'facebook_ios' => false,
        'google_ios' => false,
        'facebook_android' => true,
        'google_android' => false,
    ];

    const payment = [
        'stripe' => true,
        'conekta' => false
    ];

    // const conekta_key = "key_LiS5ishyiSuzq8sEhz5ahg";
    const conekta_key = "key_UgnZqZxkdu5HBTHehznnbw";

}
