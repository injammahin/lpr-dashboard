<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $fillable = ['name', 'display_name'];

    public function detections()
    {
        return $this->hasMany(Detection::class);
    }
}
