<?php

namespace App\Http\Controllers\Abonnement;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\User;
use App\Models\Forfait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AbonnementController extends Controller
{
    public function create()
    {
        // Récupère les noms des forfaits et leurs nombres de produits
        $nomForfaits = NomForfait::select('id_nomForfait', 'nom_forfait', 'nbr_produits')->get();

        // Récupère les durées disponibles
        $durees = DureeForfait::select('id_duree', 'nbr_mois')->get();

        return view('abonnements.create', compact('nomForfaits', 'durees'));
    }


public function store(Request $request, $forfait_id)
{
    $forfait = Forfait::findOrFail($forfait_id);

    $abonnement = Abonnement::create([
        'user_id' => Auth::id(),
        'forfait_id' => $forfait->id,
        'date_debut' => now(),
        'date_fin' => now()->addDays($forfait->duree),
        'statut' => 'en_attente',
        'reference_paiement' => null,
    ]);

    return redirect()->route('dashboard')
                 ->with('error', 'Abonnement créé avec succès.');
}

    public function showForfaitDetails($forfait_id)
    {
        // Récupérer l'id du vendeur connecté
        $vendeurId = Auth()->guard('vendeur')->id();

        // Récupérer le magasin du vendeur connecté
        $magasin = Magasin::where('vendeur_id', $vendeurId)->firstOrFail();

        // Récupérer le forfait avec ses détails
        $forfait = Forfait::with('dureeForfait', 'nomForfait')->findOrFail($forfait_id);

        // Vérifier s'il existe un abonnement actif pour le magasin
        $dernierAbonnement = Abonnement::where('magasin_id', $magasin->id_magasin)
                                        ->where('statut', 'actif')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

        if ($dernierAbonnement) {
            $ancienNomForfaitId = $dernierAbonnement->forfait->nom_forfait_id; // ID du forfait actuel
            $nouveauNomForfaitId = $forfait->nom_forfait_id; // ID du nouveau forfait

            // Convertir la date de fin en instance de Carbon
            $finAncienAbonnement = \Carbon\Carbon::parse($dernierAbonnement->fin);

            // Calcul de la durée restante en mois
            $dureeRestante = $finAncienAbonnement->diffInMonths(now());

            // Vérifier si le nouveau forfait est inférieur au précédent et si la durée restante est > 1 mois
            if ($nouveauNomForfaitId < $ancienNomForfaitId && $dureeRestante > 2){
                // dd($nouveauNomForfaitId,$ancienNomForfaitId,$dureeRestante);
                return redirect()->route('magasin.showViewVendeur', $magasin->id_magasin)
                    ->with('message', 'Vous ne pouvez pas souscrire à un forfait de type different lorsque votre abonnement actuel reste plus de 2 mois .');
            }
            if ($nouveauNomForfaitId > $ancienNomForfaitId && $dureeRestante > 2){
                // dd($nouveauNomForfaitId,$ancienNomForfaitId,$dureeRestante);
                return redirect()->route('magasin.showViewVendeur', $magasin->id_magasin)
                    ->with('message', 'Vous ne pouvez pas souscrire à un forfait de type different lorsque votre abonnement actuel reste plus de 2 mois .');
            }
        }

        // Passer les informations du forfait à la vue
        return view('abonnements.details', compact('forfait'));
    }




    // Récupère les forfaits liés via AJAX
    public function fetchForfaits(Request $request)
    {
        $nomForfaitId = $request->nom_forfait_id;

        // Récupérer les forfaits liés
        $forfaits = Forfait::where('nom_forfait_id', $nomForfaitId)
            ->with('dureeForfait') // Relation avec la durée du forfait
            ->get();

        return response()->json($forfaits);
    }

    public function create2()
{
    $users = User::orderBy('name')->get();

    $forfaits = Forfait::where('is_active', true)
        ->orderBy('montant')
        ->get();

    return view('abonnements.manuel', compact(
        'users',
        'forfaits'
    ));
}

   
}
