<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = false;

    protected $table = 'transactions';

    protected $fillable = [
        'account_id',
        'recipient_account_id',
        'user_id',
        'type_id',
        'amount',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
