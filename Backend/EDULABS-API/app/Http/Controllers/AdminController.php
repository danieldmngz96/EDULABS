<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // 🔹 Obtener configuraciones del sistema
    public function settings()
    {
        return response()->json([
            'cuota_global_kb' => Configuracion::getValue('cuota_global_kb', 10240),
            'extensiones_prohibidas' => Configuracion::getValue('extensiones_prohibidas', 'exe,bat,js,php,sh'),
        ]);
    }

    // 🔹 Actualizar configuraciones del sistema
    public function updateSettings(Request $request)
    {
        $request->validate([
            'cuota_global_kb' => 'nullable|integer|min:1024', // mínimo 1MB
            'extensiones_prohibidas' => 'nullable|string',
        ]);

        if ($request->has('cuota_global_kb')) {
            Configuracion::setValue('cuota_global_kb', $request->cuota_global_kb);
        }

        if ($request->has('extensiones_prohibidas')) {
            Configuracion::setValue('extensiones_prohibidas', $request->extensiones_prohibidas);
        }

        return response()->json([
            'message' => 'Configuraciones actualizadas correctamente',
            'config' => [
                'cuota_global_kb' => Configuracion::getValue('cuota_global_kb'),
                'extensiones_prohibidas' => Configuracion::getValue('extensiones_prohibidas'),
            ]
        ]);
    }

    // 🔹 Listar grupos existentes
    public function groupsIndex()
    {
        return response()->json(Grupo::all());
    }

    // 🔹 Crear grupo nuevo
    public function groupsStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'cuota_mb' => 'nullable|integer|min:1',
        ]);

        $grupo = Grupo::create($request->all());

        return response()->json([
            'message' => 'Grupo creado correctamente',
            'grupo' => $grupo,
        ]);
    }

    // 🔹 Actualizar grupo
    public function groupsUpdate(Request $request, Grupo $grupo)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'cuota_mb' => 'nullable|integer|min:1',
        ]);

        $grupo->update($request->all());

        return response()->json([
            'message' => 'Grupo actualizado correctamente',
            'grupo' => $grupo,
        ]);
    }

    // 🔹 Eliminar grupo
    public function groupsDestroy(Grupo $grupo)
    {
        $grupo->delete();

        return response()->json(['message' => 'Grupo eliminado correctamente']);
    }

    // 🔹 Asignar grupo a usuario
    public function assignUserGroup(Request $request, User $user)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
        ]);

        $user->update(['grupo_id' => $request->grupo_id]);

        return response()->json(['message' => 'Usuario asignado al grupo correctamente']);
    }

    // 🔹 Establecer cuota individual
    public function setUserQuota(Request $request, User $user)
    {
        $request->validate([
            'cuota_mb' => 'required|integer|min:1',
        ]);

        $user->update(['cuota_mb' => $request->cuota_mb]);

        return response()->json(['message' => 'Cuota personalizada actualizada correctamente']);
    }
}
