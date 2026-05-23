<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Inventory extends Model
{
    use HasFactory, LogsActivity;

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