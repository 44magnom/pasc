@extends('app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3>{{ $matiere->matiere }}</h3>

        <div>
            <a href="{{ route('matieres.show', $matiere->id) }}"
               class="btn btn-secondary">
                Retour
            </a>

            <a href="{{ route('chapitres.createForMatiere', $matiere->id) }}"
               class="btn btn-primary">
                Ajouter un chapitre
            </a>
        </div>

    </div>

    <table class="table table-bordered table-hover">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Chapitre</th>
                <th>Nombre de notes</th>
                <th width="180">Actions</th>
            </tr>
        </thead>

        <tbody>

        @forelse($matiere->chapitres as $chapitre)

            <tr>

                <td>{{ $chapitre->id }}</td>

                <td>{{ $chapitre->chapitre }}</td>

                <td>{{ $chapitre->notes->count() }}</td>

                <td>

                    <a href="{{ route('chapitres.show', $chapitre->id) }}"
                       class="btn btn-success btn-sm">
                        Ouvrir
                    </a>

                    <a href="{{ route('chapitres.edit', $chapitre->id) }}"
                       class="btn btn-warning btn-sm">
                        Modifier
                    </a>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="4" class="text-center">
                    Aucun chapitre enregistré.
                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection