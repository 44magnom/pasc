@extends('app')

@section('content')

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3 revision-header">

    <p class="mb-0 revision-title">

        @if(Route::is('revision.generale'))

            Révision générale

        @else

            Révision :
            {{ $notes->first()?->chapitre?->matiere?->matiere ?? 'Aucune matière' }},
            {{ $notes->first()?->chapitre?->chapitre ?? 'Aucun chapitre' }}

        @endif

    </p>


    @if(Route::is('revision.generale'))

        <form id="formValidation"
              action="{{ route('revision.valider') }}"
              method="POST">

            @csrf

            <button type="button"
                    class="btn btn-link p-0 btn-validation"
                    id="btnValider"
                    title="Valider la session">

                <i class="bi bi-check-circle-fill fs-4"></i>

            </button>

        </form>

    @endif

</div>



@if($notes->isEmpty())

    <div class="alert alert-info text-center mt-4">
        🎉 Aucune note à réviser aujourd'hui.
    </div>

@else

<div class="card shadow-lg mt-4 mb-5 carte-revision">

    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <strong id="nomMatiere">
                {{ $notes->first()?->chapitre?->matiere?->matiere ?? 'Aucune matière' }}
            </strong>
            <br>

            <small class="text-muted" id="nomChapitre">
                {{ $notes->first()?->chapitre?->chapitre ?? 'Aucun chapitre' }}
            </small>
        </div>

        <span id="numeroCarte">
            1 / {{ $notes->count() }}
        </span>

    </div>

   <div class="card-body">

    <div id="contenuCarte"
         class="mb-4"
         style="white-space: pre-wrap;">
        {!! $notes->first()?->recto ?? '' !!}
    </div>

    <hr>

    <div id="reponseCarte"
         style="display:none; white-space: pre-wrap;">
        {!! $notes->first()?->verso ?? '' !!}
    </div>

</div>

</div>

@endif
</div>

<div class="floating-actions">

    <button class="btn btn-secondary rounded-pill shadow" id="precedent">
        ⬅ Précédent
    </button>

    <button class="btn btn-success rounded-pill shadow" id="suivant">
        Suivant ➡
    </button>

</div>

@endsection

@push('styles')

<style>

.floating-actions{
    position: fixed;
    left: 20px;
    right: 20px;
    bottom: 80px;
    display: flex;
    justify-content: space-between;
    z-index: 1000;
    pointer-events: none;
}

.floating-actions button{
    pointer-events: auto;
}

</style>

@endpush

@push('scripts')

<script>

const notes = @json($notes);

let index = 0;
let reponseVisible = false;

const boutonSuivant = document.getElementById('suivant');
const boutonPrecedent = document.getElementById('precedent');

function afficherCarte()
{
    if (notes.length === 0) {
        return;
    }

    document.getElementById("nomMatiere").textContent =
        notes[index].chapitre?.matiere?.matiere ?? '';

    document.getElementById("nomChapitre").textContent =
        notes[index].chapitre?.chapitre ?? '';

    document.getElementById("contenuCarte").innerHTML =
        notes[index].recto;

    document.getElementById("reponseCarte").innerHTML =
        notes[index].verso;

    // Cacher la réponse au début
    document.getElementById("reponseCarte").style.display = "none";

    document.getElementById("numeroCarte").textContent =
        (index + 1) + " / " + notes.length;

    boutonPrecedent.disabled = (index === 0);

    // Au début de chaque carte
    reponseVisible = false;

    boutonSuivant.disabled = false;
    boutonSuivant.textContent = "Voir la réponse";
}


/* =========================
   BOUTON VOIR RÉPONSE / SUIVANT
========================= */

boutonSuivant.addEventListener('click', function () {

    // 1. La réponse n'est pas encore visible
    if (!reponseVisible) {

        document.getElementById("reponseCarte").style.display = "block";

        reponseVisible = true;

        if (index < notes.length - 1) {
            boutonSuivant.textContent = "Suivant ➡";
        } else {
            boutonSuivant.textContent = "↻ Recommencer";
        }

        return;
    }

    // 2. Dernière carte : recommencer depuis le début
    if (index === notes.length - 1) {

        index = 0;

        afficherCarte();

        return;
    }

    // 3. Passer à la carte suivante
    index++;

    afficherCarte();

});

/* =========================
   BOUTON PRÉCÉDENT
========================= */

boutonPrecedent.addEventListener('click', function () {

    if (index > 0) {

        index--;

        afficherCarte();

    }

});


/* =========================
   VALIDATION DE LA SESSION
========================= */

const btnValider = document.getElementById('btnValider');

if (btnValider) {

    btnValider.addEventListener('click', function () {

        const confirmation = confirm(
`Voulez-vous vraiment valider cette session de révision ?

Conséquences :
• Toutes les notes prévues aujourd'hui seront considérées comme révisées.
• Leur prochaine date de révision sera recalculée.
• Elles ne seront plus disponibles aujourd'hui.

Confirmer ?`
        );

        if (confirmation) {

            document.getElementById('formValidation').submit();

        }

    });

}


/* Première carte */

if (notes.length > 0) {

    afficherCarte();

}

</script>

@endpush
@push('styles')

<style>

.floating-actions{
    position: fixed;
    left: 20px;
    right: 20px;
    bottom: 80px;
    display: flex;
    justify-content: space-between;
    z-index: 1000;
    pointer-events: none;
}

.floating-actions button{
    pointer-events: auto;
}

/* Mise en forme des notes CKEditor */

#contenuCarte,
#reponseCarte{
    line-height: 1.8;
    font-size: 1rem;
}

#contenuCarte p,
#reponseCarte p{
    margin-bottom: .8rem;
}

#contenuCarte ul,
#reponseCarte ul,
#contenuCarte ol,
#reponseCarte ol{
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

#contenuCarte h1,
#reponseCarte h1{
    font-size: 2rem;
    margin-bottom: 1rem;
}

#contenuCarte h2,
#reponseCarte h2{
    font-size: 1.6rem;
    margin-bottom: .9rem;
}

#contenuCarte h3,
#reponseCarte h3{
    font-size: 1.3rem;
    margin-bottom: .8rem;
}

#contenuCarte table,
#reponseCarte table{
    width:100%;
    border-collapse:collapse;
    margin:15px 0;
}

#contenuCarte table,
#contenuCarte th,
#contenuCarte td,
#reponseCarte table,
#reponseCarte th,
#reponseCarte td{
    border:1px solid #dee2e6;
}

#contenuCarte th,
#contenuCarte td,
#reponseCarte th,
#reponseCarte td{
    padding:8px;
}

#contenuCarte img,
#reponseCarte img{
    max-width:100%;
    height:auto;
    border-radius:8px;
}

</style>

@endpush

@push('styles')

<style>

.carte-revision {
    background-color: #FFF9ED;
    color: #2F2A24;
}

.carte-revision .card-header {
    background-color: #F3EBDD;
    color: #2F2A24;
}

</style>

@endpush

@push('styles')

<style>

@media (max-width: 576px) {

    .carte-revision {
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        border-left: 0;
        border-right: 0;
        border-radius: 0;
    }

}

</style>

@endpush

@push('styles')

<style>

.revision-header {
    background-color: #F3EBDD;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #E2D5C2;
}

.revision-title {
    color: #4A4036;
}

.btn-validation {
    color: #6F7D55;
}

.btn-validation:hover {
    color: #4F5D3A;
}

</style>

@endpush