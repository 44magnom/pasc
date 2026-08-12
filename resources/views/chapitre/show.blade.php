@extends('app')

@section('content')
@if(session('note_created'))

    <script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const chapitreId =
                {{ session('note_chapitre_id') }};


            /*
            |--------------------------------------------------------------------------
            | Ouvrir IndexedDB
            |--------------------------------------------------------------------------
            */

            const request =
                indexedDB.open(
                    'nafarbox',
                    6
                );


            request.onsuccess =
                function (event) {

                    const db =
                        event.target.result;


                    /*
                    |--------------------------------------------------------------------------
                    | Vérifier le store
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !db.objectStoreNames.contains(
                            'brouillons'
                        )
                    ) {

                        console.log(
                            'ℹ️ Store brouillons inexistant'
                        );

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Supprimer le brouillon
                    |--------------------------------------------------------------------------
                    */

                    const transaction =
                        db.transaction(
                            'brouillons',
                            'readwrite'
                        );


                    const store =
                        transaction.objectStore(
                            'brouillons'
                        );


                    const brouillonId =
                        'brouillon_note_' +
                        chapitreId;


                    store.delete(
                        brouillonId
                    );


                    transaction.oncomplete =
                        function () {

                            console.log(
                                '🗑️ Brouillon supprimé après création de la note :',
                                brouillonId
                            );

                        };


                    transaction.onerror =
                        function (event) {

                            console.error(
                                '❌ Erreur suppression brouillon :',
                                event.target.error
                            );

                        };

                };


            request.onerror =
                function (event) {

                    console.error(
                        '❌ Impossible d’ouvrir IndexedDB :',
                        event.target.error
                    );

                };

        }
    );

    </script>

@endif
<div class="container mt-4">
    @if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
    <div class="container mt-4">

        {{-- Liste des brouillons --}}
        @include('partials.liste-brouillons')


        {{-- Contenu de la page --}}
        @yield('content')

    </div>

<div class="card shadow mb-4"
     style="background-color:#F8F3EB; border:1px solid #D2B48C;">

    <div class="card-body text-center">

        <h3 class="mb-1 fw-bold" style="color:#654321;">
            {{ $chapitre->matiere->matiere }}
        </h3>

        <div class="d-flex justify-content-center align-items-center gap-2">

            <p class="mb-0 fs-5" style="color:#654321;">
                {{ $chapitre->chapitre }}
            </p>

<a href="{{ route('chapitres.gerernote', $chapitre->id) }}"
   style="color:#654321;"
   title="Gérer les notes">

    <i class="bi bi-gear-fill fs-4"></i>

</a>

        </div>

    </div>

</div>

<div class="d-flex justify-content-between mb-4">

    <a href="{{ route('chapitres.createForMatiere', $chapitre->matiere_id) }}"
       class="btn"
       style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">
        ⬅ Retour huhuh
    </a>

    <a href="{{ route('revision.chapitre', $chapitre->id) }}"
       class="btn"
       style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">
        🃏 Réviser le chapitre
    </a>

</div>

<div class="text-end my-4 ">
<a href="{{ route('notes.creates', $chapitre->id) }}"
   class="btn"
    style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">

        ➕ Ajouter une note
    </a>
    </div>

<table class="table table-bordered align-middle">

    <thead>
        <tr>
            <th style="background-color:#F8F3EB; color:#654321; border-bottom:2px solid #D2B48C;">#</th>
            <th style="background-color:#F8F3EB; color:#654321; border-bottom:2px solid #D2B48C;">Recto</th>
            <th style="background-color:#F8F3EB; color:#654321; border-bottom:2px solid #D2B48C;">Actions</th>
        </tr>
    </thead>

    <tbody>

    @forelse($chapitre->notes as $note)

        <tr>

            <td class="text-center">
                {{ $loop->iteration }}
            </td>

            <td class="text-start">

<a href="#"
   class="text-decoration-none"
   style="color:#654321;"
      data-id="{{ $note->id }}"
   data-bs-toggle="modal"
   data-bs-target="#noteModal"
   data-recto="{{ $note->recto }}"
   data-verso="{{ $note->verso }}">

    {{ Str::limit($note->recto, 80) }}

</a>
        

</td>

            <!-- <td class="text-center"> -->

                <!-- <div class="d-flex justify-content-center gap-2">

<a href="{{ route('notes.edit', $note->id) }}"
   class="btn btn-sm"
   style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">
    Modifier
</a>


                    
                </div> -->
                <td class="text-center">
    <form action="{{ route('notes.toggle', $note->id) }}"
          method="POST"
          onclick="event.stopPropagation();">

        @csrf
        @method('PATCH')

        <input
            type="checkbox"
            class="form-check-input fs-5"
            onchange="this.form.submit()"
            {{ $note->is_revised ? 'checked' : '' }}>

    </form>

            </td>

        </tr>

    @empty

        <tr>
            <td colspan="3" class="text-center">
                Aucune note dans ce chapitre.
            </td>
        </tr>

    @endforelse

    </tbody>

</table>
<a href="{{ route('chapitres.export', $chapitre->id) }}"
   class="btn btn-export">
    <i class="bi bi-download me-2"></i>
    Exporter ce chapitre
</a>
@endsection

@push('styles')
<style>
    .btn-export{
    background:#F8F4EC;
    color:#654321;
    border:1px solid #D2B48C;
    border-radius:12px;
    padding:10px 18px;
    font-weight:600;
    transition:all .25s ease;
}

.btn-export:hover{
    background:#FFFDF9;
    color:#654321;
    border-color:#8B5E3C;
    box-shadow:0 4px 12px rgba(101,67,33,.12);
}
</style>


@endPush