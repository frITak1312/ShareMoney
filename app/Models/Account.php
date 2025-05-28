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

    public function getBalance()
    {
        return $this->balance;
    }

    public function setBalance($balance): void
    {
        $this->balance = $balance;
        $this->save();
    }

    public function getName()
    {
        return $this->name;
    }
    public function getOwnerNameAttribute()
    {
        return $this->users()->wherePivot('role', 'admin')->first()->full_name;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'account_memberships')
            ->using(AccountMemberships::class)
            ->withPivot('role', 'joined_at');
    }
}
