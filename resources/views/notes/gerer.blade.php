@extends('app')

@section('content')

<div class="container mt-4">
@if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="card shadow mb-4"
     style="background-color:#F8F3EB; border:1px solid #D2B48C;">

    <div class="card-body text-center">

        <h3 class="mb-1 fw-bold" style="color:#654321;">
            {{ $chapitre->matiere->matiere }}
        </h3>

        <p class="mb-0 fs-5" style="color:#654321;">
            {{ $chapitre->chapitre }}
        </p>

    </div>

</div>

<div class="d-flex justify-content-between mb-4">

    <a href="{{ route('chapitres.show', $chapitre->id) }}"
       class="btn"
       style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">
        ⬅ Retour
    </a>

    <a href="{{ route('notes.creates', $chapitre->id) }}"
       class="btn"
       style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">
        ➕ Ajouter une note
    </a>

</div>

<table class="table table-bordered align-middle">

    <thead>
        <tr>
            <th style="background-color:#F8F3EB; color:#654321;">#</th>

            <th style="background-color:#F8F3EB; color:#654321;">
                Recto
            </th>

            <th class="text-center"
                style="background-color:#F8F3EB; color:#654321;">
                Modifier
            </th>
        </tr>
    </thead>

    <tbody>

    @forelse($chapitre->notes as $note)

        <tr>

            <td class="text-center">
                {{ $loop->iteration }}
            </td>

            <td>
                {{ Str::limit($note->recto, 120) }}
            </td>

            <td class="text-center">

                <a href="{{ route('notes.edit', $note->id) }}"
                   class="btn btn-sm"
                   style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">

                    <i class="bi bi-pencil-square"></i>

                </a>

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