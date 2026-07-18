<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmploiDuTempsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
DB::table('emplois_du_temps')->insert([
    // Lundi
    ['jour_id' => 1, 'matiere_id' => 1, 'commentaire' => null],
    ['jour_id' => 1, 'matiere_id' => 3, 'commentaire' => null],
    ['jour_id' => 1, 'matiere_id' => 17, 'commentaire' => null],

    // Mardi
    ['jour_id' => 2, 'matiere_id' => 2, 'commentaire' => null],
    ['jour_id' => 2, 'matiere_id' => 4, 'commentaire' => null],
    ['jour_id' => 2, 'matiere_id' => 18, 'commentaire' => null],

    // Mercredi
    ['jour_id' => 3, 'matiere_id' => 5, 'commentaire' => null],
    ['jour_id' => 3, 'matiere_id' => 6, 'commentaire' => null],
    ['jour_id' => 3, 'matiere_id' => 8, 'commentaire' => null],

    // Jeudi
    ['jour_id' => 4, 'matiere_id' => 7, 'commentaire' => null],
    ['jour_id' => 4, 'matiere_id' => 9, 'commentaire' => null],
    ['jour_id' => 4, 'matiere_id' => 10, 'commentaire' => null],

    // Vendredi
    ['jour_id' => 5, 'matiere_id' => 11, 'commentaire' => null],
    ['jour_id' => 5, 'matiere_id' => 12, 'commentaire' => null],
    ['jour_id' => 5, 'matiere_id' => 13, 'commentaire' => null],

    // Samedi
    ['jour_id' => 6, 'matiere_id' => 14, 'commentaire' => null],
    ['jour_id' => 6, 'matiere_id' => 15, 'commentaire' => null],
    ['jour_id' => 6, 'matiere_id' => 16, 'commentaire' => null],

    // Dimanche
    ['jour_id' => 7, 'matiere_id' => 19, 'commentaire' => null],
    ['jour_id' => 7, 'matiere_id' => 20, 'commentaire' => 'Révision Droit du travail théorie'],
    ['jour_id' => 7, 'matiere_id' => 21, 'commentaire' => 'Cas pratiques Inspection du travail'],
]);
    }
}
