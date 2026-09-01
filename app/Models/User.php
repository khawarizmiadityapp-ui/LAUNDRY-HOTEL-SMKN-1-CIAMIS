<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google2fa_secret',
        'role',
        'division',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'google2fa_secret',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Get all transactions created by this user
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Get all laundry tasks assigned to this user (as petugas)
     */
    public function laundryTasks()
    {
        return $this->hasMany(LaundryTask::class, 'petugas_id');
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return in_array(strtolower((string) $this->role), ['super_admin', 'super admin', 'superadmin'], true);
    }

    /**
     * Check if user is an Admin (either Super Admin or Admin)
     */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || strtolower((string) $this->role) === 'admin';
    }

    /**
     * Check if user is Staff/Petugas
     */
    public function isStaff(): bool
    {
        return strtolower((string) $this->role) === 'staff';
    }

    /**
     * Get formatted role display name for UI
     */
    public function getRoleDisplayNameAttribute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'Super Admin';
        }

        if (strtolower((string) $this->role) === 'admin') {
            return 'Admin';
        }

        if ($this->isStaff()) {
            return 'Petugas / Staff';
        }

        return ucfirst((string) ($this->role ?? 'User'));
    }

    /**
     * Get CSS badge classes for the user's role
     */
    public function getRoleBadgeClassAttribute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'bg-purple-50 text-purple-700 border-purple-200';
        }

        if (strtolower((string) $this->role) === 'admin') {
            return 'bg-indigo-50 text-indigo-700 border-indigo-200';
        }

        return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    }
}

