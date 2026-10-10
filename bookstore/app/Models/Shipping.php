<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'buy_date', 'ship_date'];

    protected $casts = [
        'buy_date' => 'date',
        'ship_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}