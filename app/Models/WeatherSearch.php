<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherSearch extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'city',
        'condition',
        'temperature',
        'humidity',
        'wind_kph',
        'local_time',
        'is_favorite',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
