@extends('app')

@section('content')

<div class="container mt-5">

    <h2>Modifier une matière</h2>

    <form action="{{ route('matieres.update', $matiere->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nom de la matière</label>

            <input type="text"
                   name="matiere"
                   class="form-control"
                   value="{{ old('matiere', $matiere->matiere) }}"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Enregistrer
        </button>

    </form>

</div>

@endsection