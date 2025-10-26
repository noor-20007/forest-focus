<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FocusSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'duration',
        'success',
        'session_date'
    ];

    protected $casts = [
        'success' => 'boolean',
        'session_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
