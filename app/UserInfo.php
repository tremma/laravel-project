<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    // use HasFactory;

    protected $guarded = [], $table = 'user_info';
}