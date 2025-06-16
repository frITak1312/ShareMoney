<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable
        = [
            'id',
            'name',
            'balance',
        ];

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

    public function isMember($userId)
    {
        return $this->users->contains('id', $userId);
    }

    public function transactions()
    {
        return Transaction::where(function ($query) {
            $query->where('account_id', $this->id)
                ->whereNotNull('user_id');
        })
            ->orWhere(function ($query) {
                $query->where('recipient_account_id', $this->id)
                    ->whereNull('user_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
