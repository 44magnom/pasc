@extends('app')

@section('content')

<div class="container mt-4">
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

            <a href="{{ route('chapitres.edit', $chapitre->id) }}"
               style="color:#654321;"
               title="Modifier le chapitre">

                <i class="bi bi-pencil-square"></i>

            </a>

        </div>

    </div>

</div>

<div class="d-flex justify-content-between mb-4">

    <a href="{{ route('chapitres.createForMatiere', $chapitre->matiere_id) }}"
       class="btn"
       style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">
        ⬅ Retour
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
   class="btn btn-sm"
   style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">
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