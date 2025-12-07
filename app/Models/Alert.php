<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['plate', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
