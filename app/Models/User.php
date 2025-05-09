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

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute()
    {
        return strtoupper($this->name.' '.$this->surname);
    }
}
