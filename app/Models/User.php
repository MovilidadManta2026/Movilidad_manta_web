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
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    public function isAdmin(): bool
    {
        return $this->role === 'administrador';
    }

    public function canManageModule(string $module): bool
    {
        if ($this->role === 'administrador') {
            return true;
        }

        return match ($this->role) {
            'planificacion' => in_array($module, ['mision_vision', 'convocatorias', 'transparencia', 'lotaip', 'pdfs', 'servicios', 'textos'], true),
            'comunicacion' => in_array($module, ['inicio', 'mision_vision', 'boletines', 'convocatorias', 'noticias', 'fotos', 'foros', 'textos'], true),
            default => false,
        };
    }
}
