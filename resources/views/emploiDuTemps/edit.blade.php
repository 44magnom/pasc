@extends('app')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h4 class="mb-0">
                Modifier l'emploi du temps
            </h4>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form id="formUpdate"
                  action="{{ route('emplois.update', $emploi->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                {{-- Jour --}}
                <div class="mb-3">

                    <select
                        name="jour_id"
                        class="form-select @error('jour_id') is-invalid @enderror"
                        required>

                        <option value="">
                            Choisir un jour
                        </option>

                        @foreach($jours as $jour)

                            <option
                                value="{{ $jour->id }}"
                                {{ old('jour_id', $emploi->jour_id) == $jour->id ? 'selected' : '' }}>

                                {{ $jour->nom }}

                            </option>

                        @endforeach

                    </select>

                    @error('jour_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Matière --}}
                <div class="mb-3">

                    <select
                        name="matiere_id"
                        class="form-select @error('matiere_id') is-invalid @enderror"
                        required>

                        <option value="">
                            Choisir une matière
                        </option>

                        @foreach($matieres as $matiere)

                            <option
                                value="{{ $matiere->id }}"
                                {{ old('matiere_id', $emploi->matiere_id) == $matiere->id ? 'selected' : '' }}>

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

                {{-- Commentaire --}}
                <div class="mb-4">

                    <label class="form-label">

                        Commentaire
                        <span class="text-muted">(facultatif)</span>

                    </label>

                    <textarea
                        name="commentaire"
                        rows="3"
                        class="form-control @error('commentaire') is-invalid @enderror"
                        placeholder="Exemple : réviser en priorité le chapitre 3">{{ old('commentaire', $emploi->commentaire) }}</textarea>

                    @error('commentaire')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </form>

            <div class="d-flex justify-content-between align-items-center">

                <a href="{{ route('emplois.create') }}"
                   class="btn btn-outline-secondary">

                    ⬅ Retour

                </a>

                <div class="d-flex gap-2">

                    <form action="{{ route('emplois.destroy', $emploi->id) }}"
                          method="POST"
                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette matière de l\'emploi du temps ?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-outline-danger">

                            <i class="bi bi-trash"></i>
                            Supprimer

                        </button>

                    </form>

                    <button
                        type="submit"
                        form="formUpdate"
                        class="btn btn-primary">

                        <i class="bi bi-check-lg"></i>
                        Mettre à jour

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection