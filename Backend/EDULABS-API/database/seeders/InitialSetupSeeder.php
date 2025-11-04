<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use App\Models\Grupo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialSetupSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleUser = Role::firstOrCreate(['name' => 'user']);

        $grupoMarketing = Grupo::firstOrCreate(['name' => 'Marketing'], ['quota_bytes' => null]);
        $grupoDev = Grupo::firstOrCreate(['name' => 'Desarrolladores'], ['quota_bytes' => null]);

        // Global defaults
        // Default global quota 10 MB
        Configuracion::set('global_quota_bytes', (string) (10 * 1024 * 1024));
        // Default banned extensions list as CSV
        Configuracion::set('banned_extensions_csv', 'exe,bat,js,php,sh');

        // Admin user
        User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password123'),
            'role_id' => $roleAdmin->id,
            'grupo_id' => $grupoDev->id,
            'quota_bytes' => null,
        ]);

        // Normal user
        User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'name' => 'Usuario',
            'password' => Hash::make('password123'),
            'role_id' => $roleUser->id,
            'grupo_id' => $grupoMarketing->id,
            'quota_bytes' => null,
        ]);
    }
}


