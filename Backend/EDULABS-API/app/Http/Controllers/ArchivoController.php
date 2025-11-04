<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Archivo;
use App\Models\Configuracion;
use ZipArchive;

class ArchivoController extends Controller
{
    /**
     * Subir un archivo
     */
// Subir archivo con límite 10 MB
    public function upload(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240', // 10 MB
        ]);

        $user = $request->user(); // Usa Sanctum

        if (!$user) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $file = $request->file('archivo');
        $originalName = $file->getClientOriginalName();
        $storedName = uniqid() . '.' . $file->getClientOriginalExtension();
        $extension = strtolower($file->getClientOriginalExtension());
        $sizeKb = round($file->getSize() / 1024);

        // Validar ZIP
        if ($extension === 'zip') {
            $zip = new ZipArchive();
            if ($zip->open($file->getRealPath()) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $name = $zip->getNameIndex($i);
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (in_array($ext, ['exe','bat','php'])) { // ejemplo prohibidos
                        $zip->close();
                        return response()->json([
                            'error' => "Archivo prohibido dentro del ZIP: $name"
                        ], 422);
                    }
                }
                $zip->close();
            } else {
                return response()->json(['error'=>'No se pudo abrir el ZIP'],422);
            }
        }

        // Guardar archivo
        $path = $file->storeAs('uploads/'.$user->id, $storedName, 'public');

        $record = Archivo::create([
            'usuario_id' => $user->id,
            'nombre_original' => $originalName,
            'nombre_guardado' => $storedName,
            'extension' => $extension,
            'tamaño_kb' => $sizeKb,
            'ruta' => $path,
        ]);

        return response()->json([
            'mensaje'=>'Archivo subido correctamente',
            'archivo'=>[
                'id'=>$record->id,
                'nombre_original'=>$record->nombre_original,
                'tamaño_kb'=>$record->tamaño_kb,
            ]
        ],201);
    }



    /**
     * Listar archivos del usuario
     */
    public function listar(Request $request)
    {
        $user = $request->user();

        $archivos = Archivo::where('usuario_id', $user->id)
            ->select('id', 'nombre_original', 'tamaño_kb', 'creado_en')
            ->orderBy('creado_en', 'desc')
            ->get();

        return response()->json($archivos);
    }

    /**
     * Eliminar archivo
     */
    public function eliminar($id, Request $request)
    {
        $user = $request->user();
        $archivo = Archivo::findOrFail($id);

        if ($archivo->usuario_id !== $user->id && $user->role?->name !== 'admin') {
            return response()->json(['error' => 'No autorizado para eliminar este archivo.'], 403);
        }

        Storage::disk('public')->delete($archivo->ruta);
        $archivo->delete();

        return response()->json(['mensaje' => 'Archivo eliminado correctamente.']);
    }

    /**
     * Obtener cuota asignada en KB
     */
    private function getAssignedQuotaKb(int $userId): ?int
    {
        $row = DB::table('users')
            ->leftJoin('grupos', 'users.grupo_id', '=', 'grupos.id')
            ->where('users.id', $userId)
            ->select('users.quota_bytes as user_quota', 'grupos.quota_bytes as group_quota')
            ->first();

        if (!$row) return null;

        if (!is_null($row->user_quota)) return (int) round($row->user_quota / 1024);
        if (!is_null($row->group_quota)) return (int) round($row->group_quota / 1024);

        $global = Configuracion::get('global_quota_bytes');
        return $global !== null ? (int) round($global / 1024) : null;
    }

    /**
     * Extensiones prohibidas
     */
    private function getBannedExtensions(): array
    {
        $csv = Configuracion::get('banned_extensions_csv', '');
        if (!$csv) return [];
        return array_values(array_filter(array_map(fn($v) => strtolower(trim($v)), explode(',', $csv))));
    }
}
