<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'category',
        'price',
        'stock',
        'day_type',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
