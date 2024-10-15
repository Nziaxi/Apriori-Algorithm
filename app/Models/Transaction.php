<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    // Kolom-kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'transaction_date', // Tanggal transaksi
        'menu_code',        // Kode menu
        'quantity',         // Kuantitas
        'total_price',      // Total harga
    ];

    // Mendefinisikan relasi dengan model Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_code', 'code');
    }
}
