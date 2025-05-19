<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountMemberships extends Pivot
{
    protected $table = 'account_memberships';

    protected $fillable = [
        'user_id',
        'account_id',
        'role',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isModerator()
    {
        return $this->role === 'moderator';
    }
}


