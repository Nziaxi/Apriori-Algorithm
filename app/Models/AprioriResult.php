<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AprioriResult extends Model
{
    use HasFactory;

    protected $fillable = ['itemset', 'support', 'confidence'];
    
    protected $casts = [
        'itemset' => 'array',
        'support' => 'float',
        'confidence' => 'array',
    ];
}
