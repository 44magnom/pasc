
<div class="card border shadow-sm mt-4" style="border-color:#D2B48C;">
    <div class="card-body">

        <h5 class="fw-bold" style="color:#654321;">
            <i class="bi bi-whatsapp text-success"></i>
            Confirmation du paiement
        </h5>

        <p class="text-muted">
            Après votre paiement, remplissez ce formulaire puis cliquez sur <strong>Envoyer</strong>.
        </p>

        <form id="formPaiement">

            <div class="mb-3">
                <label>Prénom et nom</label>
                <input type="text" id="nom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Numéro Wave utilisé</label>
                <input type="tel" id="telephone" class="form-control" placeholder="77 000 00 00" required>
            </div>

            <div class="mb-3">
                <label>Montant payé (FCFA)</label>
                <input type="number" id="montant" class="form-control" required>
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Date du paiement</label>
                    <input type="date" id="date" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Heure du paiement</label>
                    <input type="time" id="heure" class="form-control" required>
                </div>

            </div>

            <button type="button"
                    class="btn btn-success w-100"
                    onclick="envoyerWhatsapp()">

                <i class="bi bi-whatsapp"></i>
                Envoyer sur WhatsApp

            </button>

        </form>

    </div>
</div>




@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const now = new Date();

    // Date au format YYYY-MM-DD
    document.getElementById('date').value = now.toISOString().split('T')[0];

    // Heure au format HH:MM
    const heures = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    document.getElementById('heure').value = `${heures}:${minutes}`;

});

function envoyerWhatsapp() {

    let nom = document.getElementById('nom').value;
    let telephone = document.getElementById('telephone').value;
    let montant = document.getElementById('montant').value;
    let date = document.getElementById('date').value;
    let heure = document.getElementById('heure').value;

    let message =
`Bonjour NafarBox,

Je confirme avoir effectué mon paiement.

👤 Nom : ${nom}
📱 Numéro Wave : ${telephone}
💰 Montant : ${montant} FCFA
📅 Date : ${date}
🕒 Heure : ${heure}

Merci d'activer mon abonnement.`;

    let url = "https://wa.me/221785903265?text=" + encodeURIComponent(message);

    window.open(url, "_blank");
}
</script>

@endpush