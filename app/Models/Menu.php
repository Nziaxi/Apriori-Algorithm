<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Kolom-kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'code',      // Kode Menu
        'name',      // Nama Menu
        'category',  // Kategori Menu (makanan utama, minuman, snack, dessert)
    ];
}
