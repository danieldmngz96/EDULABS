<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones'; // asegúrate que el nombre coincida con tu tabla
    protected $fillable = ['clave', 'valor'];

    // 🔹 Obtener valor de configuración (con valor por defecto)
    public static function getValue($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    // 🔹 Establecer o actualizar valor de configuración
    public static function setValue($clave, $valor)
    {
        return self::updateOrCreate(
            ['clave' => $clave],
            ['valor' => $valor]
        );
    }
}
