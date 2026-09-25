<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\Auditable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active', 'phone', 'customer_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use Auditable, HasFactory, Notifiable;

    /** Mirrors the column default so new, unsaved/unrefreshed models count as active. */
    protected $attributes = ['is_active' => true];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** An active account with one of the staff roles in config/roles.php — may use the management panel. */
    public function isStaff(): bool
    {
        return $this->is_active && array_key_exists((string) $this->role, config('roles.roles'));
    }

    public function hasPermission(string $permission): bool
    {
        if (! $this->isStaff()) {
            return false;
        }

        $granted = config("roles.roles.{$this->role}.permissions", []);

        return in_array('*', $granted, true) || in_array($permission, $granted, true);
    }

    public function getRoleLabelAttribute(): string
    {
        return config("roles.roles.{$this->role}.label", ucfirst((string) $this->role));
    }

    public function assignedEnquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'assigned_to');
    }

    public function scopeStaff(Builder $query): Builder
    {
        return $query->whereIn('role', array_keys(config('roles.roles')));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }
}
