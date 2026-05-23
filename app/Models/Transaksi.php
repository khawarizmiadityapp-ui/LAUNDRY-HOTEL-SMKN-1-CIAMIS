<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Customer;
use App\Models\TransaksiDetail;
use App\Traits\LogsActivity;

class Transaksi extends Model
{
    use HasFactory, LogsActivity;

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

    protected $casts = [
        'weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
    public function pos()
    {
        return $this->hasMany(LaundryTask::class);
    }

    // Alias relasi untuk kompatibilitas pemanggilan tasks()
    public function tasks()
    {
        return $this->hasMany(LaundryTask::class);
    }
}
