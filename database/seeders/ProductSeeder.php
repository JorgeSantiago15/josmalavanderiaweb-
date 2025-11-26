<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // --- SERVICIOS BÁSICOS (Cuentan para el reporte) ---
            [
                'nombre' => 'Lavadora Normal (Carga)',
                'precio' => 30.00,
                'tipo' => 'servicio',
                'reporte_categoria' => 'lavadora', // Se contará como lavadora
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Lavadora Grande',
                'precio' => 40.00,
                'tipo' => 'servicio',
                'reporte_categoria' => 'lavadora',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Secadora (Carga)',
                'precio' => 40.00,
                'tipo' => 'servicio',
                'reporte_categoria' => 'secadora', // Se contará como secadora
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Solo Secado (Sin lavado)',
                'precio' => 50.00,
                'tipo' => 'servicio',
                'reporte_categoria' => 'secadora',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Servicio de Doblado',
                'precio' => 20.00,
                'tipo' => 'servicio',
                'reporte_categoria' => 'doblado', // Genera comisión de $5
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Servicio Completo (Lavar, Secar, Productos, Doblar)',
                'precio' => 110.00,
                'tipo' => 'servicio',
                'reporte_categoria' => null, // Ojo: Al elegir este, internamente deberíamos sumar 1 lav, 1 sec y 1 dob
                'created_at' => now(), 'updated_at' => now()
            ],

            // --- PRODUCTOS (Insumos) ---
            ['nombre' => 'Jabón (250ml)', 'precio' => 7.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Suavitel (250ml)', 'precio' => 7.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Bolsa de Empaque', 'precio' => 6.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Jabón (1 Litro)', 'precio' => 25.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Suavitel (1 Litro)', 'precio' => 25.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Desengrasante (250ml)', 'precio' => 10.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Pinol (250ml)', 'precio' => 7.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Toallita Secadora (c/u)', 'precio' => 1.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            
            // --- PRODUCTOS DE MARCA ---
            ['nombre' => 'Suavitel de Marca', 'precio' => 27.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Suavitel Complete', 'precio' => 30.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Pinol Marca', 'precio' => 16.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cloro Cloralex', 'precio' => 16.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Jabón Roma', 'precio' => 18.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Jabón Carisma', 'precio' => 18.00, 'tipo' => 'producto', 'reporte_categoria' => null, 'created_at' => now(), 'updated_at' => now()],

            // --- DESCUENTOS (Precios Negativos) ---
            [
                'nombre' => 'Descuento: Trae su Jabón',
                'precio' => -7.00, 
                'tipo' => 'descuento', 
                'reporte_categoria' => null,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nombre' => 'Descuento: Trae su Suavitel',
                'precio' => -7.00, 
                'tipo' => 'descuento', 
                'reporte_categoria' => null,
                'created_at' => now(), 'updated_at' => now()
            ],
        ];

        DB::table('productos')->insert($productos);
    }
}