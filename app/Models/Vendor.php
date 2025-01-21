<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillabel= [
        'user_id',
        'name',
        'description',
        'location',
        'phone',
        'city',
        'verivy_status',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
