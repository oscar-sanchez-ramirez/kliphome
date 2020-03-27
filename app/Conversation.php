<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $appends = ['contact_name'];
    public function getContactNameAttribute()
    {
      return $this->user()->first(['name','lastName','avatar']);
    }
    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
