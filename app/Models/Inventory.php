<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    // nama tabel (optional, kalau beda dari default)
    protected $table = 'inventories';

    // field yang boleh diisi (WAJIB biar bisa create/update)
    protected $fillable = [
        'name',
        'category',
        'quantity',
        'type',
    ];

    // default value (optional)
    protected $attributes = [
        'quantity' => 0,
    ];

    // casting tipe data (biar aman)
    protected $casts = [
        'quantity' => 'integer',
    ];
}