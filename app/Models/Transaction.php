<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = false;

    protected $table = 'transactions';

    protected $fillable = [
        'account_id',
        'user_id',
        'recipient_account_id',
        'type_id',
        'amount',
        'description',
        'type_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function recipientAccount()
    {
        return $this->belongsTo(Account::class, 'recipient_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
