<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detection extends Model
{
    protected $fillable = [
        'camera_id', 'plate', 'ts', 'date_str', 'time_str',
        'file_path', 'file_size'
    ];

    public function camera()
    {
        return $this->belongsTo(Camera::class);
    }
}
