<?php

namespace App\Models\Access;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User.
 */
class Profile extends Authenticatable
{
    use Notifiable;
    protected $table = 'profiles';

    protected $fillable = ['userid', 'birthday', 'country', 'state', 'city', 'affiliate_url'];
}
