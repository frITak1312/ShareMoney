<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    protected $fillable
        = [
            'name',
            'surname',
            'username',
            'email',
            'password',
            'avatar_path',
        ];

    protected $hidden
        = [
            'password',
        ];

    public function getFullNameAttribute(): string
    {
        return strtoupper($this->name.' '.$this->surname);
    }

    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_memberships')
            ->using(AccountMemberships::class)
            ->withPivot('role');
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public static function getUserByUsername(string $username): ?User
    {
        return User::where('username', $username)->first();
    }
}

