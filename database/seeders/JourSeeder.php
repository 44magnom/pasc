<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                DB::table('jours')->insert([
            ['nom' => 'Lundi', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Mardi', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Mercredi', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Jeudi', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Vendredi', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Samedi', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Dimanche', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
