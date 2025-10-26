<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Streak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_streak',
        'last_session_date',
        'longest_streak'
    ];

    protected $casts = [
        'last_session_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
