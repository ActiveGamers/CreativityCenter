<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdmissionRequestSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * Admission requests are submitted by families through the admission
     * form, so no fake request is seeded here. The seeder exists so every
     * model has a matching seeder and the database stays free of placeholder
     * applications.
     */
    public function run(): void
    {
        //
    }
}
