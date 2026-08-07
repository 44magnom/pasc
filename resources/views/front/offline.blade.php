
<div id="offlineAlert"
     class="alert alert-warning d-none shadow-sm">

    <i class="bi bi-wifi-off me-2"></i>

    Vous êtes actuellement hors connexion.

    <br>

    Vous pouvez continuer à créer vos notes.
    Elles seront synchronisées automatiquement dès que la connexion Internet sera rétablie.

</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

    const offlineAlert = document.getElementById('offlineAlert');

    function verifierConnexion() {

        if (navigator.onLine) {

            offlineAlert.classList.add('d-none');

        } else {

            offlineAlert.classList.remove('d-none');

        }

    }

    verifierConnexion();

    window.addEventListener('online', verifierConnexion);
    window.addEventListener('offline', verifierConnexion);

});
</script>

@endpush