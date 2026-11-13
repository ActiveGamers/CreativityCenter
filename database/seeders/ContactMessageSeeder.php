<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * Contact messages are submitted by visitors through the contact form, so
     * no fake message is seeded here. The seeder exists so every model has a
     * matching seeder and the database stays free of placeholder requests.
     */
    public function run(): void
    {
        //
    }
}
