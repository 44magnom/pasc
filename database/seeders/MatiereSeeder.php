<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     DB::table('matieres')->delete();
DB::statement('ALTER TABLE matieres AUTO_INCREMENT = 1;');

        DB::table('matieres')->insert([
            ['matiere' => 'Droit du travail théorie', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Droit du travail pratique', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Sécurité sociale', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Santé et sécurité au travail', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'GRH', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => "Procédure et pratique de l'inspection du travail", 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Médecine du travail', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Déontologie professionnelle', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Normes internationales', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => "Politique d'emploi", 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => "Organisation et fonctionnement de l'entreprise", 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Droit administratif', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Droit constitutionnel', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => "Organisation et fonctionnement de l'administration", 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Finances publiques', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Économie générale', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Techniques de communication', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Rédaction administrative', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Droit de la fonction publique et déontologie', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Française', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['matiere' => 'Religion', 'nbr_note' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
