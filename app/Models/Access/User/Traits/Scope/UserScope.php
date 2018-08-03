<?php

namespace App\Models\Access\User\Traits\Scope;

/**
 * Class UserScope.
 */
trait UserScope
{
    /**
     * @param $query
     * @param bool $confirmed
     *
     * @return mixed
     */
    public function scopeConfirmed($query, $confirmed = true)
    {
        return $query->where('confirmed', $confirmed)->where('id', '>', 1);;
    }

    /**
     * @param $query
     * @param bool $status
     *
     * @return mixed
     */
    public function scopeActive($query, $status = true)
    {
        return $query->where('status', $status)->where('id', '>', 1);
    }

    public function scopeApproved($query, $approve = 1)
    {
        return $query->where('approve', $approve)->where('id', '>', 1);
    }

    public function scopeAdmin($query, $admin = 1)
    {
        return $query->where('isadmin', $admin)->where('id', '>', 1);
    }
}
