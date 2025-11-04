<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_name',
        'storage_path',
        'size_bytes',
    ];

    // ⚠️ Esto evita que Eloquent intente insertar created_at/updated_at
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


