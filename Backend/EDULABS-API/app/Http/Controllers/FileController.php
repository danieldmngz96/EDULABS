<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\Archivo;
use App\Models\User;
use App\Models\Configuracion;
use App\Models\Grupo;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $archivos = Archivo::where('usuario_id', $user->id)->get();
        return response()->json($archivos);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // 1️⃣ Validar que haya archivo
        if (!$request->hasFile('archivo')) {
            return response()->json(['error' => 'No se envió ningún archivo.'], 400);
        }

        $file = $request->file('archivo');
        $extension = strtolower($file->getClientOriginalExtension());
        $tamañoKB = $file->getSize() / 1024;

        // 2️⃣ Validar extensiones prohibidas
        $extensionesProhibidas = Configuracion::getValue('extensiones_prohibidas', 'exe,bat,js,php,sh');
        $listaProhibidas = array_map('trim', explode(',', $extensionesProhibidas));

        if (in_array($extension, $listaProhibidas)) {
            return response()->json(['error' => "El tipo de archivo '.{$extension}' no está permitido."], 400);
        }

        // 3️⃣ Si es un ZIP, verificar su contenido
        if ($extension === 'zip') {
            $zip = new ZipArchive();
            if ($zip->open($file->getRealPath()) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $nombreInterno = $zip->getNameIndex($i);
                    $extInterna = strtolower(pathinfo($nombreInterno, PATHINFO_EXTENSION));
                    if (in_array($extInterna, $listaProhibidas)) {
                        $zip->close();
                        return response()->json(['error' => "El archivo '{$nombreInterno}' dentro del .zip no está permitido."], 400);
                    }
                }
                $zip->close();
            }
        }

        // 4️⃣ Verificar cuota de almacenamiento
        $cuota = $this->obtenerCuotaUsuario($user);
        $usoActual = Archivo::where('usuario_id', $user->id)->sum('tamaño_kb');

        if (($usoActual + $tamañoKB) > $cuota) {
            return response()->json([
                'error' => "Cuota excedida. Límite: {$cuota} KB. Uso actual: {$usoActual} KB."
            ], 400);
        }

        // 5️⃣ Guardar archivo
        $nombreGuardado = uniqid() . '.' . $extension;
        $ruta = $file->storeAs('uploads/' . $user->id, $nombreGuardado, 'public');

        $archivo = Archivo::create([
            'usuario_id' => $user->id,
            'nombre_original' => $file->getClientOriginalName(),
            'nombre_guardado' => $nombreGuardado,
            'extension' => $extension,
            'tamaño_kb' => $tamañoKB,
            'ruta' => $ruta,
        ]);

        return response()->json([
            'mensaje' => 'Archivo subido correctamente.',
            'archivo' => $archivo
        ]);
    }

    private function obtenerCuotaUsuario($user)
    {
        // Prioridad: usuario > grupo > global
        if ($user->cuota_kb) {
            return $user->cuota_kb;
        }

        if ($user->grupo && $user->grupo->cuota_kb) {
            return $user->grupo->cuota_kb;
        }

        return Configuracion::getValue('cuota_global_kb', 10240); // por defecto 10 MB
    }
}
