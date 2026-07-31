<?php

namespace App\Http\Controllers\Paiement;

use App\Http\Controllers\Controller;
use App\Models\Forfait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class PaydunyaController extends Controller
{

        public function store(Request $request)
        {
            $idForfait = $request->query('id_forfait'); // Récupérer l'ID du forfait passé via la route
           

            // Récupérer les informations du forfait à partir de la base de données
            $forfait = Forfait::findOrFail($idForfait); // Assurez-vous que le modèle "Forfait" existe
         
            // Configuration de PayDunya
            \Paydunya\Setup::setMasterKey(env('P_MasterKey'));
            \Paydunya\Setup::setPublicKey(env('P_PublicKey_T'));
            \Paydunya\Setup::setPrivateKey(env('P_PrivateKey_T'));
            \Paydunya\Setup::setToken(env('P_TokenKey_T'));
            \Paydunya\Setup::setMode(env('P_mode')); // Mode de test ou de production

            // Configuration des informations de l'entreprise
            \Paydunya\Checkout\Store::setName("NafarBox");
            \Paydunya\Checkout\Store::setTagline("Réviser intelligemment");
            \Paydunya\Checkout\Store::setPhoneNumber("77952364");
            \Paydunya\Checkout\Store::setPostalAddress("Dakar Plateau - Etablissement kheweul");
            \Paydunya\Checkout\Store::setWebsiteUrl("https://nafarbox.com");
            \Paydunya\Checkout\Store::setLogoUrl("https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png");
          \Paydunya\Checkout\Store::setCallbackUrl(
    route('paydunya.callback', ['forfait' => $forfait->id])
);
            
            // \Paydunya\Checkout\Store::setCallbackUrl("https://nafarbox.com/api/get-forfaits/".$forfait->id_forfait);

            \Paydunya\Checkout\Store::setCancelUrl(route('paydunya.cancel'));
            \Paydunya\Checkout\Store::setReturnUrl(route('paydunya.succes'));

            // Création de la facture
            $invoice = new \Paydunya\Checkout\CheckoutInvoice();

            $invoice->addItem(
                $forfait->nom, // Nom du forfait
                1, // Quantité
                $forfait->montant, // Prix unitaire
                $forfait->montant, // Prix total
                // $forfait->description // Description
            );
            // Données personnalisées
$invoice->addCustomData("user_id", Auth::id());
$invoice->addCustomData("forfait_id", $forfait->id);

            $invoice->setTotalAmount($forfait->montant);

            // Traitement du paiement
            if ($invoice->create()) {
                $urlFacture = $invoice->getInvoiceUrl();
                return Redirect::to($urlFacture); // Redirection vers la page de paiement
            } else {
                return back()->withErrors($invoice->response_text); // Retourner une erreur en cas d'échec
            }
        }

public function callback(Request $request)
{

    \Log::info('CALLBACK REÇU', $request->all());

    dd($request->all());
    
    // Vérification de la signature
    if ($_POST['data']['hash'] !== hash('sha512', env('P_MasterKey'))) {
        return response()->json(['message' => 'Signature invalide'], 403);
    }

    // Paiement réussi ?
    if ($_POST['data']['status'] != 'completed') {
        return response()->json(['message' => 'Paiement non validé'], 400);
    }

    $userId = $_POST['data']['custom_data']['user_id'];
    $forfaitId = $_POST['data']['custom_data']['forfait_id'];

    $user = User::findOrFail($userId);
    $forfait = Forfait::findOrFail($forfaitId);

    Abonnement::create([
        'user_id' => $user->id,
        'forfait_id' => $forfait->id,
        'date_debut' => now(),
        'date_fin' => now()->addDays($forfait->duree),
        'statut' => 'actif',
        'reference_paiement' => $_POST['data']['token'] ?? null,
    ]);

    $user->update([
        'is_subscribed' => true,
    ]);

    return response()->json([
        'success' => true,
    ]);
}

    }
