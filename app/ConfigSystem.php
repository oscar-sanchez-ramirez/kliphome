<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ConfigSystem extends Model
{
    const socialmedia = [
        'success' => true,
        'facebook_ios' => true,
        'google_ios' => true,
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
