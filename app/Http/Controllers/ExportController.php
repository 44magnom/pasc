<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ExportController extends Controller
{
    public function exportWord()
    {
        $matieres = Matiere::where('user_id', Auth::id())
            ->with('chapitres.notes')
            ->orderBy('matiere')
            ->get();

        $phpWord = new PhpWord();

        $section = $phpWord->addSection();

        $section->addTitle('NAFARBOX', 1);
        $section->addText('Export du : '.now()->format('d/m/Y H:i'));

        foreach ($matieres as $matiere) {

            $section->addTitle($matiere->matiere, 2);

            foreach ($matiere->chapitres as $chapitre) {

                $section->addTitle($chapitre->chapitre, 3);

                foreach ($chapitre->notes as $note) {

                    $section->addText('Question', ['bold' => true]);
                    $section->addText(strip_tags($note->recto));

                    $section->addText('Réponse', ['bold' => true]);
                    $section->addText(strip_tags($note->verso));

                    $section->addText('--------------------------------');
                }
            }

            $section->addPageBreak();
        }

        $tempFile = storage_path('app/nafarbox.docx');

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}