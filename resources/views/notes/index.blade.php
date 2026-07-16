@extends('app')

@section('title', 'Liste des notes')

@section('content')

<h2 class="mb-4">Liste des notes</h2>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Matière</th>
            <th>Note</th>
            <th>Révisions</th>
            <th>Date de création</th>
        </tr>
    </thead>
    <tbody>
        @forelse($notes as $note)
            <tr>
                <td>{{ $note->id_note }}</td>
                <td>{{ $note->matiere->matiere }}</td>
                <td>{{ $note->note }}</td>
                <td>{{ $note->nombre_revision }}</td>
                <td>{{ $note->created_at->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Aucune note disponible.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection