@extends('app')

@section('content')

<div class="container mt-5">
    <h2>Ajouter une matière</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('matieres.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="matiere" class="form-label">Nom de la matière</label>

            <input
                type="text"
                class="form-control @error('matiere') is-invalid @enderror"
                id="matiere"
                name="matiere"
                placeholder="Entrez le nom de la matière"
                value="{{ old('matiere') }}"
                required
            >

            @error('matiere')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Ajouter
        </button>

    </form>

    <hr class="my-5">

    <h3>Liste des matières</h3>

    <table class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Matière</th>
           
                <th>voir</th>
                                   <td>
                        supprimer 
                    </td>
            </tr>
        </thead>

        <tbody>
            @forelse($matieres as $matiere)
                <tr>
                    <td>{{ $matiere->id }}</td>
    <td>
    <a href="{{ route('chapitres.createForMatiere', $matiere->id) }}">
        {{ $matiere->matiere }}
    </a>
</td>
           
                    <td>
                        <a href="{{ route('matieres.edit', $matiere->id) }}"
                        class="btn btn-warning btn-sm">
                            Modifier
                        </a>
                    </td>
                     <td>
                        <form action="{{ route('matieres.destroy', $matiere->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Voulez-vous vraiment supprimer cette matière ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">
                        Aucune matière enregistrée.
                    </td>

                </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection