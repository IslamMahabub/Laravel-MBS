<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id', 'account_no', 'account_opening_date', 'currency', 'current_balance'
    ];

    public function users()
	{
	    return $this->hasMany(Transaction::class);
	}

    public function transactions()
	{
	    return $this->belongsTo('App\User', 'user_id');
	}
}
