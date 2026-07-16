@extends('app')

@section('content')

<div class="container mt-4">

    <p class="text-muted mb-3">Révision</p>

    @if($notes->isEmpty())

        <div class="alert alert-info text-center">
            Aucune note disponible.
        </div>

    @else

        <div class="card shadow-lg mt-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <div>
                    <strong id="nomMatiere">
                        {{ $notes->first()?->chapitre?->matiere?->matiere ?? '' }}
                    </strong>

                    <br>

                    <small class="text-muted" id="nomChapitre">
                        {{ $notes->first()?->chapitre?->chapitre ?? '' }}
                    </small>
                </div>

                <span id="numeroCarte">
                    1 / {{ $notes->count() }}
                </span>

            </div>

            <div class="card-body">

                <!-- Recto -->
                <div id="contenuCarte" class="mb-4">
                    {!! $notes->first()?->recto ?? '' !!}
                </div>

                <!-- Réponse + ligne de séparation -->
                <div id="blocReponse" style="display:none;">

                    <hr>

                    <div id="reponseCarte">
                        {!! $notes->first()?->verso ?? '' !!}
                    </div>

                </div>

            </div>

        </div>

    @endif

</div>


@if($notes->isNotEmpty())

<div class="floating-actions">

    <button class="btn btn-secondary rounded-pill shadow"
            id="precedent">
        ⬅ Précédent
    </button>

    <button class="btn btn-success rounded-pill shadow"
            id="suivant">
        Voir la réponse
    </button>

</div>

@endif

@endsection


@push('styles')

<style>

/* Boutons flottants */

.floating-actions{
    position:fixed;
    left:20px;
    right:20px;
    bottom:80px;
    display:flex;
    justify-content:space-between;
    z-index:1000;
    pointer-events:none;
}

.floating-actions button{
    pointer-events:auto;
}


/* Contenu CKEditor */

#contenuCarte,
#reponseCarte{
    font-size:1rem;
    line-height:1.8;
}

#contenuCarte p,
#reponseCarte p{
    margin-bottom:.8rem;
}

#contenuCarte ul,
#contenuCarte ol,
#reponseCarte ul,
#reponseCarte ol{
    padding-left:25px;
    margin-bottom:1rem;
}

#contenuCarte h1,
#reponseCarte h1{
    font-size:2rem;
}

#contenuCarte h2,
#reponseCarte h2{
    font-size:1.6rem;
}

#contenuCarte h3,
#reponseCarte h3{
    font-size:1.3rem;
}

#contenuCarte table,
#reponseCarte table{
    width:100%;
    border-collapse:collapse;
    margin:15px 0;
}

#contenuCarte table,
#contenuCarte td,
#contenuCarte th,
#reponseCarte table,
#reponseCarte td,
#reponseCarte th{
    border:1px solid #dee2e6;
}

#contenuCarte td,
#contenuCarte th,
#reponseCarte td,
#reponseCarte th{
    padding:8px;
}

#contenuCarte img,
#reponseCarte img{
    max-width:100%;
    height:auto;
    border-radius:10px;
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
    if(notes.length === 0){
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

    /* Cacher le verso */
    document.getElementById("blocReponse").style.display = "none";

    /* Numéro de la carte */
    document.getElementById("numeroCarte").textContent =
        (index + 1) + " / " + notes.length;

    /* Réinitialiser le bouton */
    reponseVisible = false;

    boutonSuivant.textContent = "Voir la réponse";

    /* Désactiver précédent sur la première carte */
    boutonPrecedent.disabled = (index === 0);
}


/* VOIR RÉPONSE / SUIVANT / RECOMMENCER */

if(notes.length > 0){

    afficherCarte();

    boutonSuivant.addEventListener('click', function(){

        /* Afficher la réponse */
        if(!reponseVisible){

            document.getElementById("blocReponse").style.display = "block";

            reponseVisible = true;

            /* Dernière carte */
            if(index === notes.length - 1){

                boutonSuivant.textContent = "↻ Recommencer";

            }else{

                boutonSuivant.textContent = "Suivant ➡";

            }

            return;
        }


        /* Recommencer après la dernière carte */

        if(index === notes.length - 1){

            index = 0;

            afficherCarte();

            return;
        }


        /* Carte suivante */

        index++;

        afficherCarte();

    });


    /* CARTE PRÉCÉDENTE */

    boutonPrecedent.addEventListener('click', function(){

        if(index > 0){

            index--;

            afficherCarte();

        }

    });

}

</script>

@endpush