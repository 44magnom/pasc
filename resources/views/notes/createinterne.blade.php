@extends('app')

@section('content')

<div class="card-body text-center">

    <h4 class="mb-1 fw-bold" style="color:#654321;">
        {{ $chapitre->matiere->matiere }}
    </h4>

    <p class="mb-0" style="font-size:1.05rem; color:#654321;">
        {{ $chapitre->chapitre }}
    </p>

</div>


<form action="{{ route('notes.store') }}"
      method="POST"
      id="formNote">

    @csrf

    {{-- IDs --}}
    <input type="hidden"
           name="matiere_id"
           id="matiere_id"
           value="{{ $chapitre->matiere->id }}">

    <input type="hidden"
           name="chapitre_id"
           id="chapitre_id"
           value="{{ $chapitre->id }}">


    {{-- RECTO --}}
    <div class="mb-3">

        <label for="recto" class="form-label">
            Recto
        </label>

        <textarea
            id="recto"
            name="recto"
            class="form-control @error('recto') is-invalid @enderror"
            rows="2"
            required>{{ old('recto') }}</textarea>

        @error('recto')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- VERSO --}}
    <div class="mb-3">

        <label for="verso" class="form-label">
            Verso
        </label>

        <textarea
            id="verso"
            name="verso"
            class="form-control @error('verso') is-invalid @enderror"
            rows="6">{{ old('verso') }}</textarea>

        @error('verso')
            <div class="text-danger mt-1">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="text-end">

        <button type="submit"
                class="btn btn-primary">

            Enregistrer

        </button>

    </div>

</form>

@endsection


@push('styles')

<style>

.ck-editor__editable_inline {
    min-height: 150px;
}

</style>

@endpush


@push('scripts')

<script>

let versoEditor = null;


/*
|--------------------------------------------------------------------------
| CKEDITOR
|--------------------------------------------------------------------------
*/

ClassicEditor
    .create(document.querySelector('#verso'))
    .then(editor => {

        versoEditor = editor;

    })
    .catch(error => {

        console.error("Erreur CKEditor :", error);

    });


/*
|--------------------------------------------------------------------------
| FORMULAIRE
|--------------------------------------------------------------------------
*/

document.getElementById("formNote").addEventListener("submit", function(e) {

    /*
    |--------------------------------------------------------------------------
    | SI INTERNET EST DISPONIBLE
    |--------------------------------------------------------------------------
    |
    | Laravel traite normalement le formulaire.
    |
    */

    if (navigator.onLine) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | HORS CONNEXION
    |--------------------------------------------------------------------------
    */

    e.preventDefault();


    /*
    |--------------------------------------------------------------------------
    | Récupérer le contenu CKEditor
    |--------------------------------------------------------------------------
    */

    let verso = "";

    if (versoEditor) {

        verso = versoEditor.getData();

    }


    /*
    |--------------------------------------------------------------------------
    | Récupérer les données
    |--------------------------------------------------------------------------
    */

    const note = {

        local_id: crypto.randomUUID(),

        matiere_id:
            document.getElementById("matiere_id").value,

        chapitre_id:
            document.getElementById("chapitre_id").value,

        recto:
            document.getElementById("recto").value,

        verso: verso,

        nombre_revision: 0,

        prochaine_revision:
            new Date().toISOString().split("T")[0],

        is_revised: true,

        is_synced: false,

        created_at: new Date()

    };


    console.log("📦 Note hors connexion :", note);


    /*
    |--------------------------------------------------------------------------
    | Enregistrer dans IndexedDB
    |--------------------------------------------------------------------------
    */

    saveOfflineNote(note);


    /*
    |--------------------------------------------------------------------------
    | Message utilisateur
    |--------------------------------------------------------------------------
    */

    alert(
        "Votre note a été enregistrée hors connexion. " +
        "Elle sera synchronisée automatiquement lorsque la connexion reviendra."
    );


    /*
    |--------------------------------------------------------------------------
    | Vider le formulaire
    |--------------------------------------------------------------------------
    */

    document.getElementById("recto").value = "";


    if (versoEditor) {

        versoEditor.setData("");

    }

});

</script>

@endpush