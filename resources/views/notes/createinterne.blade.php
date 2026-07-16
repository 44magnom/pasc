@extends('app')

@section('content')

<div class="container mt-4">

    <!-- Bannière -->
    <div class="card bg-primary text-white shadow mb-4">
        <div class="card-body text-center">

            <h4 class="mb-1">
                {{ $chapitre->matiere->matiere }}
            </h4>

            <p class="mb-0 fs-5">
                {{ $chapitre->chapitre }}
            </p>

        </div>
    </div>

    <h4>Ajouter une note</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('notes.store') }}"
          method="POST"
          id="formNote">

        @csrf

        <!-- IDs cachés -->
        <input type="hidden"
               name="matiere_id"
               value="{{ $chapitre->matiere->id }}">

        <input type="hidden"
               name="chapitre_id"
               value="{{ $chapitre->id }}">

        <!-- Recto -->
        <div class="mb-3">

            <label for="recto" class="form-label">
                Recto
            </label>

            <textarea
                id="recto"
                name="recto"
                class="form-control @error('recto') is-invalid @enderror"
                rows="1"
                required>{{ old('recto') }}</textarea>

            @error('recto')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <!-- Verso -->
        <div class="mb-3">

            <label for="verso" class="form-label">
                Verso
            </label>

            <textarea
                id="verso"
                name="verso"
                class="form-control @error('verso') is-invalid @enderror"
                rows="6">{{ old('verso') }}</textarea>

            @error('verso')
                <div class="text-danger mt-1">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="text-end">

            <button type="submit" class="btn btn-primary">
                Enregistrer
            </button>

        </div>

    </form>

</div>

@endsection


@push('styles')

<style>

.ck-editor__editable_inline {
    min-height: 180px;
}

</style>

@endpush


@push('scripts')

<script>

let versoEditor;

ClassicEditor
    .create(document.querySelector('#verso'))
    .then(editor => {

        versoEditor = editor;

    })
    .catch(error => {

        console.error(error);

    });

document.getElementById('formNote').addEventListener('submit', function () {

    if (versoEditor) {

        document.getElementById('verso').value =
            versoEditor.getData();

    }

});

</script>

@endpush