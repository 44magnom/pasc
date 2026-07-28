<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Chapitre;
use Illuminate\Support\Facades\Auth;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ExportController extends Controller
{
public function exportTexte()
{
    $matieres = Matiere::where('user_id', Auth::id())
        ->with('chapitres.notes')
        ->orderBy('matiere')
        ->get();

    $contenu = "NAFARBOX\n";
    $contenu .= "Export du : " . now()->format('d/m/Y H:i') . "\n\n";

    foreach ($matieres as $matiere) {

        $contenu .= "=====================================\n";
        $contenu .= "MATIÈRE : {$matiere->matiere}\n";
        $contenu .= "=====================================\n\n";

        foreach ($matiere->chapitres as $chapitre) {

            $contenu .= "Chapitre : {$chapitre->chapitre}\n";
            $contenu .= str_repeat('-', 40) . "\n";

            foreach ($chapitre->notes as $note) {

                $contenu .= "Question :\n";
                $contenu .= strip_tags($note->recto) . "\n\n";

                $contenu .= "Réponse :\n";
                $contenu .= strip_tags($note->verso) . "\n\n";

                $contenu .= str_repeat('=', 60) . "\n\n";
            }

            $contenu .= "\n";
        }

        $contenu .= "\n\n";
    }

    return response($contenu)
        ->header('Content-Type', 'text/plain; charset=UTF-8')
        ->header('Content-Disposition', 'attachment; filename="nafarbox.txt"');
}


public function exportChapitre($id)
{
    $chapitre = Chapitre::whereHas('matiere', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['matiere', 'notes'])
        ->findOrFail($id);

    $contenu = "NAFARBOX\n";
    $contenu .= "Export du : " . now()->format('d/m/Y H:i') . "\n\n";

    $contenu .= "Matière : " . $chapitre->matiere->matiere . "\n";
    $contenu .= "Chapitre : " . $chapitre->chapitre . "\n";
    $contenu .= str_repeat("=", 60) . "\n\n";

    foreach ($chapitre->notes as $i => $note) {

        $contenu .= "Note " . ($i + 1) . "\n\n";

        $contenu .= "Question :\n";
        $contenu .= strip_tags($note->recto) . "\n\n";

        $contenu .= "Réponse :\n";
        $contenu .= strip_tags($note->verso) . "\n\n";

        $contenu .= str_repeat("-", 60) . "\n\n";
    }

    $nom = str_replace(' ', '_', $chapitre->chapitre) . '.txt';

    return response($contenu)
        ->header('Content-Type', 'text/plain; charset=UTF-8')
        ->header('Content-Disposition', 'attachment; filename="'.$nom.'"');
}
}