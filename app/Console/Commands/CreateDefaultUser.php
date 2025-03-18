<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateDefaultUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:default-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea dos usuarios predeterminados si la tabla de usuarios está vacía';

    /**
     * Ejecuta el comando.
     *
     * @return void
     */
    public function handle()
    {
        // Verifica si la tabla 'users' está vacía
        if (User::count() == 0) {
            // Crear el usuario predeterminado
            User::create([
                'name' => 'usuario1',
                'email' => 'usuario1@gmail.com',
                'plan' => 'sencillo',
                'max_devices' => 2,
                'password' => Hash::make('usuario1'),
            ]);

            User::create([
                'name' => 'usuario2',
                'email' => 'usuario2@gmail.com',
                'plan' => 'sencillo',
                'max_devices' => 2,
                'password' => Hash::make('usuario2'),
            ]);

            $this->info('Usuario predeterminado creado exitosamente.');
        } else {
            $this->info('La tabla de usuarios no está vacía.');
        }
    }
}

