<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package App\Model
 */
class Role extends Model
{
    /**
     * Relationship many to many with users table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Model\User', 'user_role', 'role_id', 'user_id');
    }
}
