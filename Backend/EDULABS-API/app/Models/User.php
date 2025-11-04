<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios'; // nombre de tu tabla real

    /**
     * Columnas que se pueden llenar masivamente
     */
    protected $fillable = [
        'nombre',       // coincide con la DB
        'email',
        'password',
        'rol_id',
        'grupo_id',
        'cuota_mb',     // o 'quota_bytes' según tu DB
    ];

    /**
     * Columnas ocultas al serializar
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast de columnas
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Accessor para que $user->name funcione
     */
    public function getNameAttribute()
    {
        return $this->attributes['nombre'];
    }

    /**
     * Relación con Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    /**
     * Relación con Grupo
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    /**
     * Relación con Archivos
     */
    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }
}
