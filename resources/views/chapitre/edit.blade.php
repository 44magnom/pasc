@extends('app')

@section('content')

<div class="container mt-4">

    <h4>Modifier le chapitre</h4>

    <form action="{{ route('chapitres.update', $chapitre->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nom du chapitre</label>

            <input
                type="text"
                name="chapitre"
                class="form-control"
                value="{{ old('chapitre', $chapitre->chapitre) }}"
                required>
        </div>

        <button class="btn btn-primary">
            Enregistrer
        </button>

        <a href="{{ route('chapitres.show', $chapitre->id) }}"
           class="btn btn-secondary">
            Annuler
        </a>

    </form>
        <form action="{{ route('chapitres.destroy', $chapitre->id) }}"
              method="POST"
              onsubmit="return confirm('Voulez-vous vraiment supprimer ce chapitre ? Toutes les notes associées seront également supprimées.')">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-danger btn-sm">
                Supprimer
            </button>

        </form>
</div>

@endsection