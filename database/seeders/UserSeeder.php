<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Paciente;
use App\Models\Medico;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Administrador
        $admin = User::create([
            'nombre_completo' => 'Administrador Sistema',
            'correo' => 'admin@sistema.com',
            'contrasena' => Hash::make('admin123'),
            'rol' => 'administrador',
            'activo' => true,
        ]);

        // Crear Médicos
        $medico1 = User::create([
            'nombre_completo' => 'Dr. Juan Carlos Méndez',
            'correo' => 'juan.mendez@clinica.com',
            'contrasena' => Hash::make('medico123'),
            'rol' => 'medico',
            'activo' => true,
        ]);

        $medico2 = User::create([
            'nombre_completo' => 'Dra. María Elena Rodríguez',
            'correo' => 'maria.rodriguez@clinica.com',
            'contrasena' => Hash::make('medico123'),
            'rol' => 'medico',
            'activo' => true,
        ]);

        // Crear registros de médicos
        Medico::create([
            'id_usuario' => $medico1->id_usuario,
            'especialidad' => 'Cardiología',
            'cedula_profesional' => 'CARD001',
        ]);

        Medico::create([
            'id_usuario' => $medico2->id_usuario,
            'especialidad' => 'Cardiología',
            'cedula_profesional' => 'CARD002',
        ]);

        // Crear Pacientes
        $paciente1 = User::create([
            'nombre_completo' => 'Ana Sofía García',
            'correo' => 'ana.garcia@email.com',
            'contrasena' => Hash::make('paciente123'),
            'rol' => 'paciente',
            'activo' => true,
        ]);

        $paciente2 = User::create([
            'nombre_completo' => 'Carlos Alberto López',
            'correo' => 'carlos.lopez@email.com',
            'contrasena' => Hash::make('paciente123'),
            'rol' => 'paciente',
            'activo' => true,
        ]);

        $paciente3 = User::create([
            'nombre_completo' => 'María Isabel Torres',
            'correo' => 'maria.torres@email.com',
            'contrasena' => Hash::make('paciente123'),
            'rol' => 'paciente',
            'activo' => true,
        ]);

        // Crear registros de pacientes
        Paciente::create([
            'id_usuario' => $paciente1->id_usuario,
            'fecha_nacimiento' => '1985-03-15',
            'sexo' => 'F',
        ]);

        Paciente::create([
            'id_usuario' => $paciente2->id_usuario,
            'fecha_nacimiento' => '1978-07-22',
            'sexo' => 'M',
        ]);

        Paciente::create([
            'id_usuario' => $paciente3->id_usuario,
            'fecha_nacimiento' => '1992-11-08',
            'sexo' => 'F',
        ]);

        $this->command->info('Usuarios de prueba creados exitosamente!');
        $this->command->info('Administrador: admin@sistema.com / admin123');
        $this->command->info('Médicos: juan.mendez@clinica.com / medico123');
        $this->command->info('Pacientes: ana.garcia@email.com / paciente123');
    }
}
