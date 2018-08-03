<?php

namespace App\Models\Access;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User.
 */
class Invite extends Authenticatable
{
    use Notifiable,
        SoftDeletes;
    protected $table = 'invites';

    protected $fillable = ['user_id', 'invited_id'];
}
