@extends('app')

@section('content')

<div class="container mt-4">

<div class="card bg-primary text-white shadow mb-4">

    <div class="card-body text-center">

        <h3 class="mb-1">
            {{ $chapitre->matiere->matiere }}
        </h3>

        <p class="mb-0 fs-5">
            {{ $chapitre->chapitre }}
        </p>

    </div>

</div>

<div class="d-flex justify-content-between mb-4">

    <a href="{{ route('chapitres.createForMatiere', $chapitre->matiere_id) }}"
       class="btn btn-outline-secondary">
        ⬅ Retour
    </a>

    <a href="{{ route('revision.chapitre', $chapitre->id) }}"
       class="btn btn-success">
        🃏 Réviser le chapitre
    </a>

</div>

<div class="text-end mb-4 ">
<a href="{{ route('notes.creates', $chapitre->id) }}"
   class="btn btn-primary">

        ➕ Ajouter une note
    </a>

<table class="table table-bordered align-middle">

    <thead class="table-dark text-center">
        <tr>
            <th>#</th>
            <th>Recto</th>
            <th>Actions</th>
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
                   data-bs-toggle="modal"
                   data-bs-target="#noteModal"
                   data-recto="{{ $note->recto }}"
                   data-verso="{{ $note->verso }}">

                    {{ Str::limit($note->recto, 80) }}

                </a>

            </td>

            <td class="text-center">

                <div class="d-flex justify-content-center gap-2">

                    <a href="{{ route('notes.edit', $note->id) }}"
                       class="btn btn-warning btn-sm">
                        Modifier
                    </a>


                    
                </div>

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

@endsection