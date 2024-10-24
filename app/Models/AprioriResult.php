<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AprioriResult extends Model
{
    use HasFactory;

    protected $table = 'apriori_results';

    protected $fillable = [
        'items',
        'recommendation',
        'support',
        'confidence',
        'lift'
    ];
    
    protected $casts = [
        'items' => 'array',
        'support' => 'decimal:4',
        'confidence' => 'decimal:4',
        'lift' => 'decimal:4'
    ];
}
