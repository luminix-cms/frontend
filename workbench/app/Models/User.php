<?php

namespace Workbench\App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Luminix\Backend\Model\LuminixModel;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LuminixModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $appends = [ 'age' ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function getValidationRules(string $for): array
    {
        return match ($for) {
            'store' => [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                // 'age' => 'integer|between:18,100',
            ],
            'update' => [
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $this->id,
                'password' => 'nullable|string|min:8|confirmed',
                // 'age' => 'nullable|integer|between:18,100',
            ],
        };
    }
    

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }


    public function getAgeAttribute(): int
    {
        return $this->attributes['age'] ?? 0;
    }

}
