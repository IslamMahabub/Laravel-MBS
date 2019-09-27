<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'from_account', 'to_account', 'remark'
    ];
}
