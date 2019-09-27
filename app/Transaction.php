<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id', 'transaction_type', 'amount', 'transaction_action', 'transfer_id', 'transaction_date'
    ];

    public function accounts()
	{
	    return $this->belongsTo('App\Account', 'account_id');
	}
}
