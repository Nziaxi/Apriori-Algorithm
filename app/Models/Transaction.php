<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'menu_code',
        'quantity',
        'total_price',
    ];

    // Mendefinisikan relasi dengan model Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_code', 'code');
    }
}
