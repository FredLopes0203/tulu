<?php

namespace App\Models\Subadmin;

use App\Models\Access\User\Traits\UserAccess;
use App\Models\Subadmin\Traits\Attribute\SubadminAttribute;
use App\Models\Subadmin\Traits\Relationship\SubadminRelationship;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User.
 */
class Subadmin extends Authenticatable
{
    use Notifiable,
        SoftDeletes,
        UserAccess,
        SubadminRelationship,
        SubadminAttribute;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'first_name', 'last_name', 'email', 'phonenumber', 'password', 'status', 'organization', 'isinitial', 'approve', 'profile_picture', 'confirmation_code', 'confirmed', 'resetcode'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('access.users_table');
    }
}
