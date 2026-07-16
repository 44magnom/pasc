@extends('app')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h4 class="mb-0">
                Ajouter une matière à l'emploi du temps
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

            <form action="{{ route('emplois.store') }}" method="POST">

                @csrf

                {{-- Jour --}}
                <div class="mb-3">

                    <!-- <label for="jour_id" class="form-label">
                        Jour
                    </label> -->

                    <select
                        name="jour_id"
                        id="jour_id"
                        class="form-select @error('jour_id') is-invalid @enderror"
                        required>

                        <option value="">
                            Choisir un jour
                        </option>

                        @foreach($jours as $jour)

                            <option
                                value="{{ $jour->id }}"
                                {{ old('jour_id') == $jour->id ? 'selected' : '' }}>

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

                    <!-- <label for="matiere_id" class="form-label">
                        Matière
                    </label> -->

                    <select
                        name="matiere_id"
                        id="matiere_id"
                        class="form-select @error('matiere_id') is-invalid @enderror"
                        required>

                        <option value="">
                            Choisir une matière
                        </option>

                        @foreach($matieres as $matiere)

                            <option
                                value="{{ $matiere->id }}"
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

                {{-- Commentaire --}}
                <div class="mb-3">

                    <label for="commentaire" class="form-label">
                        Commentaire
                        <span class="text-muted">(facultatif)</span>
                    </label>

                    <textarea
                        name="commentaire"
                        id="commentaire"
                        rows="3"
                        class="form-control @error('commentaire') is-invalid @enderror"
                        placeholder="Exemple : réviser en priorité le chapitre 3">{{ old('commentaire') }}</textarea>

                    @error('commentaire')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('emplois.index') }}"
                       class="btn btn-outline-secondary">

                        ⬅ Retour

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- Tableau des emplois du temps --}}

    <div class="card shadow-sm mt-4">

        <div class="card-header">

            <h5 class="mb-0">
                Emploi du temps actuel
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0 align-middle">

                    <thead class="table-light">

                        <tr>

                            <th style="width:35%">
                                Jour
                            </th>

                            <th>
                                Matière
                            </th>
                                <th class="text-center" style="width:70px">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($emplois as $nomJour => $emploisDuJour)

                            @foreach($emploisDuJour as $index => $emploi)

                                <tr>

                                    @if($index === 0)

                                        <td
                                            rowspan="{{ $emploisDuJour->count() }}"
                                            class="fw-bold text-center align-middle">

                                            {{ $nomJour }}

                                        </td>

                                    @endif

                                    <td>

                                        {{ $emploi->matiere->matiere }}

                                    </td>
                                    <td class="text-center">

                                        <a href="{{ route('emplois.edit', $emploi->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Modifier">

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        @empty

                            <tr>

                                <td colspan="2"
                                    class="text-center text-muted py-4">

                                    Aucun emploi du temps enregistré.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
