@extends('app')

@section('content')

<div class="container mt-4">

    <h4>Ajouter une note</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('notes.store') }}" method="POST">

        @csrf

        <!-- Matière -->
        <div class="mb-3">
            <label class="form-label">Matière</label>

            <select
                name="matiere_id"
                id="matiere"
                class="form-select @error('matiere_id') is-invalid @enderror"
                required>

                <option value="">Choisir une matière</option>

                @foreach($matieres as $matiere)

                    <option value="{{ $matiere->id }}"
                        {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>

                        {{ $matiere->matiere }}

                    </option>

                @endforeach

            </select>

            @error('matiere_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <!-- Chapitre -->
        <div class="mb-3">

            <label class="form-label">Chapitre</label>

            <select
                name="chapitre_id"
                id="chapitre"
                class="form-select @error('chapitre_id') is-invalid @enderror"
                required>

                <option value="">Choisir un chapitre</option>

            </select>

            @error('chapitre_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

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

            <label class="form-label">
                Verso
            </label>
<textarea
    id="verso"
    name="verso"
    class="form-control @error('verso') is-invalid @enderror"
    rows="6">{{ old('verso') }}</textarea>
            @error('verso')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="text-end">

            <button class="btn btn-primary">
                Enregistrer
            </button>

        </div>

    </form>

</div>

@endsection

@push('scripts')

<script>

const matieres = @json($matieres);

const selectMatiere = document.getElementById('matiere');
const selectChapitre = document.getElementById('chapitre');

const ancienneMatiere = "{{ old('matiere_id') }}";
const ancienChapitre = "{{ old('chapitre_id') }}";

function chargerChapitres(matiereId)
{
    selectChapitre.innerHTML =
        '<option value="">Choisir un chapitre</option>';

    const matiere = matieres.find(m => m.id == matiereId);

    if(!matiere){
        return;
    }

    matiere.chapitres.forEach(function(chapitre){

        let selected = '';

        if(chapitre.id == ancienChapitre){
            selected = 'selected';
        }

        selectChapitre.innerHTML += `
            <option value="${chapitre.id}" ${selected}>
                ${chapitre.chapitre}
            </option>
        `;

    });
}

selectMatiere.addEventListener('change', function(){

    chargerChapitres(this.value);

});

// Recharger les chapitres si une matière était déjà sélectionnée
if(ancienneMatiere != '')
{
    chargerChapitres(ancienneMatiere);
}

</script>

@endpush

@push('scripts')

<script>

ClassicEditor
    .create(document.querySelector('#verso'))
    .catch(error => {
        console.error(error);
    });

</script>

@endpush
@push('styles')
<style>
    .ck-editor__editable_inline {
        min-height: 150px;
    }
</style>
@endpush