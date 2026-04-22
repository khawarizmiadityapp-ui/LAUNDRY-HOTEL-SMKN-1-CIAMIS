<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Customer;
use App\Models\TransaksiDetail;

class Transaksi extends Model
{
    protected $table = 'transaksi'; // sesuaikan sama nama tabel di database

    protected $fillable = [
        'transaksi_code',
        'user_id', 
        'customer_id', 
        'customer_name',
        'customer_phone',
        'service_type', 
        'weight', 
        'price_per_kg', 
        'total_price',
        'status', 
        'payment_status', 
        'payment_method',
        'notes'
    ];

    // Relasi ke User (Petugas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Detail (multi-layanan)
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    // Relasi ke Tracking Tasks
    public function tasks()
    {
        return $this->hasMany(LaundryTask::class);
    }
}