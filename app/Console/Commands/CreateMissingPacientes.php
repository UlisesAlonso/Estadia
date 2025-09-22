<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Paciente;
use Illuminate\Console\Command;

class CreateMissingPacientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pacientes:create-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear registros faltantes en la tabla pacientes para usuarios con rol paciente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando usuarios pacientes sin registro en tabla pacientes...');

        $usuariosSinPaciente = User::where('rol', 'paciente')
            ->whereDoesntHave('paciente')
            ->get();

        if ($usuariosSinPaciente->isEmpty()) {
            $this->info('No se encontraron usuarios pacientes sin registro.');
            return;
        }

        $this->info("Se encontraron {$usuariosSinPaciente->count()} usuarios sin registro de paciente.");

        $bar = $this->output->createProgressBar($usuariosSinPaciente->count());
        $bar->start();

        foreach ($usuariosSinPaciente as $usuario) {
            // Crear registro de paciente con valores por defecto
            Paciente::create([
                'id_usuario' => $usuario->id_usuario,
                'fecha_nacimiento' => '1990-01-01', // Fecha por defecto
                'sexo' => 'masculino', // Valor por defecto
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Registros de pacientes creados exitosamente.');
    }
}
