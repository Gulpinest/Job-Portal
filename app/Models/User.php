<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Roles;
use App\Models\Log;
use App\Models\Pelamar;
use App\Models\Company;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'password' => 'hashed',
        ];
    }

    public function role(string $role){
        return $this->belongsTo(Roles::class);
    }

    // Check if user has specific permission
    public function hasPermission(string $permission): bool {
        return $this->role?->hasPermission($permission) ?? false;
    }

    // Check if user has any of the given permissions
    public function hasAnyPermission(array $permissions): bool{
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
    // Check if user has all given permissions
    public function hasAllPermissions(array $permissions): bool{
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    public function getRoleName(): string{
        return $this->role?->name ?? 'No Role';
    }

 // Helper methods for role checking
    public function isAdmin(): bool{
        return $this->getRoleName() === 'admin';
    }

    public function isPelamar(): bool{
        return $this->getRoleName() === 'pelamar';
    }

    public function isCompany(): bool{
        return $this->getRoleName() === 'company';
    }

}
