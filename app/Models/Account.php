<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable
        = [
            'name',
            'balance',
        ];

    public function setBalance($balance): void
    {
        $this->balance = $balance;
        $this->save();
    }

    public function getAdminNameAttribute()
    {
        return $this->users()->wherePivot('role', 'admin')->first()->full_name;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'account_memberships')
            ->using(AccountMemberships::class)
            ->withPivot('role', 'joined_at')
            ->orderByRaw("FIELD(role, 'admin', 'moderator', 'member')");
    }

    public function getUserRole($userId)
    {
        $membership = $this->users->firstWhere('id', $userId);

        return $membership?->pivot?->role;
    }
}
