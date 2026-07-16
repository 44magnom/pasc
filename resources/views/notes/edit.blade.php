@extends('app')

@section('content')

<div class="container mt-4">

    <h4>Modifier la note</h4>

    <form action="{{ route('notes.update', $note->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Recto</label>

            <textarea
                name="recto"
                class="form-control @error('recto') is-invalid @enderror"
                rows="3"
                required>{{ old('recto', $note->recto) }}</textarea>

            @error('recto')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Verso</label>

            <textarea
                name="verso"
                class="form-control @error('verso') is-invalid @enderror"
                rows="6"
                required>{{ old('verso', $note->verso) }}</textarea>

            @error('verso')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">

            <button type="submit"
                    class="btn btn-primary">
                Enregistrer
            </button>

            <button type="submit"
                    form="deleteForm"
                    class="btn btn-danger"
                    onclick="return confirm('Voulez-vous vraiment supprimer cette note ?')">
                Supprimer
            </button>

        </div>

    </form>

    <form id="deleteForm"
          action="{{ route('notes.destroy', $note->id) }}"
          method="POST">

        @csrf
        @method('DELETE')

    </form>

</div>

@endsection