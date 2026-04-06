<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicePrice extends Model
{
    protected $fillable = ['service_name', 'service_type', 'price_per_kg', 'is_active'];
}