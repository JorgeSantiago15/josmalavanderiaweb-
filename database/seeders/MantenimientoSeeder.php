<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MantenimientoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. LIMPIAR TABLA (Para evitar duplicados si corres el seeder varias veces)
        DB::table('mantenimientos')->truncate();

        $adminId = 1; 
        $hoy = Carbon::now();

        // --- A. INFRAESTRUCTURA (Registros Únicos) ---
        $infraestructura = [
            [
                'nombre' => 'Red Hidráulica (Bombas y Tuberías)',
                'frecuencia_dias' => 90,
                'descripcion' => 'Revisar fugas en tuberías y uniones. Llaves sin sarro y faciles de girar. Equipo hidroneumático: filtro bomba, válvula aire tanque y platinos.',
            ],
            [
                'nombre' => 'Red Eléctrica (Cableado y Centros)',
                'frecuencia_dias' => 90,
                'descripcion' => 'Verificar cableado (color/temperatura). Contactos flojos. Luminarias. Centro de carga: apriete conexiones.',
            ],
            [
                'nombre' => 'Red de Drenaje',
                'frecuencia_dias' => 90,
                'descripcion' => 'Verificar tuberías PVC traseras. Uniones selladas sin fugas.',
            ],
            [
                'nombre' => 'Red de Gas y Tanque',
                'frecuencia_dias' => 60,
                'descripcion' => 'Fugas en tuberías/llaves. Mangueras secadoras (doblar). Tanque: válvulas y regulador.',
            ],
            [
                'nombre' => 'Paredes y Pintura',
                'frecuencia_dias' => 365,
                'descripcion' => 'Desgaste pintura, golpes enjarre, limpieza pelusa.',
            ],
            [
                'nombre' => 'Instalaciones Sanitarias (Baño)',
                'frecuencia_dias' => 90,
                'descripcion' => 'Fugas de agua y funcionamiento.',
            ],
            [
                'nombre' => 'Letreros y Publicidad',
                'frecuencia_dias' => 270,
                'descripcion' => 'Condiciones físicas y renovación.',
            ],
        ];

        foreach ($infraestructura as $item) {
            DB::table('mantenimientos')->insert([
                'nombre' => $item['nombre'],
                'descripcion' => $item['descripcion'],
                'categoria' => 'infraestructura',
                'tipo' => 'preventivo',
                'frecuencia_dias' => $item['frecuencia_dias'],
                // Programamos para que venzan escalonadamente o desde hoy, usaremos HOY + Frecuencia
                'fecha_programada' => $hoy->copy()->addDays($item['frecuencia_dias']),
                'usuario_id' => $adminId,
                'estado' => 'pendiente',
                'created_at' => $hoy,
                'updated_at' => $hoy,
            ]);
        }

        // --- B. MAQUINARIA (Generación Individual) ---
        
        // Lavadoras 1 al 10
        for ($i = 1; $i <= 10; $i++) {
            DB::table('mantenimientos')->insert([
                'nombre' => "Lavadora #$i - Preventivo",
                'descripcion' => "Limpieza general. Revisar ruidos, ciclos completos, temperatura motor. Lubricar bujes/engrasar.",
                'categoria' => 'maquinaria',
                'tipo' => 'preventivo',
                'frecuencia_dias' => 45,
                
                // las dejamos todas para dentro de 45 días y se van haciendo poco a poco.
                'fecha_programada' => $hoy->copy()->addDays(45), 
                'usuario_id' => $adminId,
                'estado' => 'pendiente',
                'created_at' => $hoy,
                'updated_at' => $hoy,
            ]);
        }

        // Secadoras 1 al 6
        for ($i = 1; $i <= 6; $i++) {
            DB::table('mantenimientos')->insert([
                'nombre' => "Secadora #$i - Preventivo",
                'descripcion' => "Limpieza profunda pelusa/polvo. Lubricación piezas.",
                'categoria' => 'maquinaria',
                'tipo' => 'preventivo',
                'frecuencia_dias' => 45,
                'fecha_programada' => $hoy->copy()->addDays(45),
                'usuario_id' => $adminId,
                'estado' => 'pendiente',
                'created_at' => $hoy,
                'updated_at' => $hoy,
            ]);
        }
    }
}