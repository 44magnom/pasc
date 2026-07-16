@extends('app')

@section('title', 'Liste des notes')

@section('content')

<h2 class="mb-4">Liste des Matières</h2>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Matière</th>
            <th>Nombre de fiches </th>

        </tr>
    </thead>
    <tbody>
        @forelse($matieres as $matiere)
<tr>
    <td>{{ $matiere->id }}</td>

    <td>
        <a href="{{ route('matieres.show', $matiere->id) }}">
            {{ $matiere->matiere }}
        </a>
    </td>

    <td>{{ $matiere->nbr_note }}</td>
</tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Aucune note disponible.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection