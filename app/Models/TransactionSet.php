<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionSet extends Model
{
    use HasFactory;

    protected $table = 'transaction_set';

    protected $fillable = [
        'transaction_date',
        'itemset',
    ];
}
