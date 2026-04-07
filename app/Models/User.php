<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Relations\UserRelations;
use App\Models\Scopes\UserScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    use UserRelations;
    use UserScopes;

    /**
     * Code for admin user type.
     */
    const ADMIN_TYPE = 'admin';

    /**
     * Code for customer user type.
     */
    const CUSTOMER_TYPE = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'type',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    public static function types(): array
    {
        return [
            self::ADMIN_TYPE,
            self::CUSTOMER_TYPE,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->type === self::ADMIN_TYPE;
    }

    public function isCustomer(): bool
    {
        return $this->type === self::CUSTOMER_TYPE;
    }

    public function dashboardRouteName(): string
    {
        return $this->isAdmin() ? 'admin.dashboard' : 'dashboard';
    }
}
