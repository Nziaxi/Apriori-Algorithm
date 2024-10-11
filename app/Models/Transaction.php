<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
