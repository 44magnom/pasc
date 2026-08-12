@extends('app')


@section('content')

<div class="card-body text-center">

    <h4 class="mb-1 fw-bold" style="color:#654321;">
        {{ $chapitre->matiere->matiere }}
    </h4>

    <p class="mb-0"
       style="font-size:1.05rem; color:#654321;">
        {{ $chapitre->chapitre }}
    </p>

</div>


<form action="{{ route('notes.store') }}"
      method="POST"
      id="formNote">

    @csrf


    {{-- =========================================================
         IDS
    ========================================================== --}}

    <input type="hidden"
           name="matiere_id"
           id="matiere_id"
           value="{{ $chapitre->matiere->id }}">


    <input type="hidden"
           name="chapitre_id"
           id="chapitre_id"
           value="{{ $chapitre->id }}">


    {{-- =========================================================
         RECTO
         Simple textarea
    ========================================================== --}}

    <div class="mb-3">

        <label for="recto"
               class="form-label">

            Recto

        </label>


        <textarea
            id="recto"
            name="recto"
            rows="2"
            class="form-control @error('recto') is-invalid @enderror"
            placeholder="Ex. Qu'est-ce que la souveraineté ?"
            required>{{ old('recto') }}</textarea>


        @error('recto')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =========================================================
         VERSO
         CKEditor
    ========================================================== --}}

    <div class="mb-3">

        <label for="verso"
               class="form-label">

            Verso

        </label>


        <textarea
            id="verso"
            name="verso"
            rows="6"
            class="form-control @error('verso') is-invalid @enderror">{{ old('verso') }}</textarea>


        @error('verso')

            <div class="text-danger mt-1">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- =========================================================
         BOUTON
    ========================================================== --}}

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

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | CKEDITOR
        |--------------------------------------------------------------------------
        |
        | SEUL LE VERSO utilise CKEditor.
        |
        */

        window.editorVerso = null;


        ClassicEditor
            .create(
                document.querySelector('#verso')
            )
.then(
    function (editor) {

        window.editorVerso = editor;

        console.log(
            '✅ CKEditor Verso prêt'
        );


        /*
        |--------------------------------------------------------------------------
        | NOTE JUSTE CRÉÉE
        |--------------------------------------------------------------------------
        |
        | Laravel nous indique que la note vient d'être créée.
        | On vide immédiatement le formulaire.
        |
        */

        @if(session('note_created'))

            console.log(
                '🧹 Nettoyage du formulaire après création'
            );


            const recto =
                document.getElementById('recto');


            if (recto) {

                recto.value = '';

            }


            editor.setData('');

        @endif

    }
)
            .catch(
                function (error) {

                    console.error(
                        '❌ Erreur CKEditor :',
                        error
                    );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | FORMULAIRE
        |--------------------------------------------------------------------------
        */

        const formNote =
            document.getElementById(
                'formNote'
            );


        if (!formNote) {

            console.error(
                '❌ Formulaire #formNote introuvable'
            );

            return;

        }


        formNote.addEventListener(
            'submit',
            async function (e) {


                console.log(
                    '🟢 Enregistrement demandé'
                );


                /*
                |--------------------------------------------------------------------------
                | RECTO
                |--------------------------------------------------------------------------
                */

                const recto =
                    document
                        .getElementById('recto')
                        .value
                        .trim();


                /*
                |--------------------------------------------------------------------------
                | VERSO
                |--------------------------------------------------------------------------
                */

                let verso = '';


                if (window.editorVerso) {

                    verso =
                        window.editorVerso.getData();

                }


                /*
                |--------------------------------------------------------------------------
                | Synchroniser le textarea
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById('verso')
                    .value = verso;


                /*
                |--------------------------------------------------------------------------
                | Vérifier le Recto
                |--------------------------------------------------------------------------
                */

                if (recto === '') {

                    e.preventDefault();

                    alert(
                        'Veuillez saisir le recto de la note.'
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | EN LIGNE
                |--------------------------------------------------------------------------
                */

                if (navigator.onLine) {

                    /*
                    | Laravel reçoit normalement le formulaire.
                    |
                    | Le brouillon sera supprimé sur la page
                    | de redirection grâce à la session Laravel.
                    */

                    console.log(
                        '🌐 En ligne → Laravel'
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | HORS LIGNE
                |--------------------------------------------------------------------------
                */

                e.preventDefault();


                console.log(
                    '📴 Hors ligne → IndexedDB'
                );


                /*
                |--------------------------------------------------------------------------
                | CRÉER LA NOTE LOCALE
                |--------------------------------------------------------------------------
                */

                const note = {

                    local_id:
                        crypto.randomUUID(),

                    matiere_id:
                        document
                            .getElementById(
                                'matiere_id'
                            )
                            .value,

                    chapitre_id:
                        document
                            .getElementById(
                                'chapitre_id'
                            )
                            .value,

                    recto:
                        recto,

                    verso:
                        verso,

                    nombre_revision:
                        0,

                    prochaine_revision:
                        new Date()
                            .toISOString()
                            .split('T')[0],

                    is_revised:
                        true,

                    is_synced:
                        false,

                    created_at:
                        new Date()

                };


                console.log(
                    '📦 Note hors connexion :',
                    note
                );


                /*
                |--------------------------------------------------------------------------
                | ENREGISTRER LA NOTE
                |--------------------------------------------------------------------------
                */

                try {

                    if (
                        typeof saveOfflineNote !==
                        'function'
                    ) {

                        throw new Error(
                            'saveOfflineNote() est introuvable'
                        );

                    }


                    await saveOfflineNote(
                        note
                    );


                    console.log(
                        '✅ Note enregistrée dans IndexedDB'
                    );


                } catch (error) {

                    console.error(
                        '❌ Erreur sauvegarde note :',
                        error
                    );


                    alert(
                        'Impossible d’enregistrer la note hors connexion.'
                    );


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | SUPPRIMER LE BROUILLON
                |--------------------------------------------------------------------------
                */

                if (
                    typeof window.supprimerBrouillon ===
                    'function'
                ) {

                    await window.supprimerBrouillon();

                }


                /*
                |--------------------------------------------------------------------------
                | MESSAGE
                |--------------------------------------------------------------------------
                */

                alert(
                    'Votre note a été enregistrée hors connexion. ' +
                    'Elle sera synchronisée automatiquement lorsque la connexion reviendra.'
                );


                /*
                |--------------------------------------------------------------------------
                | VIDER
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById('recto')
                    .value = '';


                if (window.editorVerso) {

                    window.editorVerso.setData('');

                }

            }
        );

    });

</script>


{{-- =============================================================
     GESTION DES BROUILLONS
============================================================= --}}

@include('partials.brouillon-note')

@endpush


