<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCity extends Model
{
    use HasFactory;

    protected $fillable = ['city', 'is_favorite', 'send_forecast'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}