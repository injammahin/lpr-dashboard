<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitedUser extends Model
{
    protected $fillable = [
        'email',
        'token',
        'accepted',
        'invited_by'
    ];
}
