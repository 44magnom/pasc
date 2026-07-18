
@extends('app')

@section('content')

@php

$total = $emplois->count();

$validees = $emplois->filter(function ($emploi) {
    return $emploi->validations->isNotEmpty();
})->count();

$pourcentage = $total > 0
    ? round(($validees / $total) * 100)
    : 0;

@endphp

<div class="container mt-4">

    <div class="text-center mb-4">

        <h3 class="fw-bold mb-1">
            Objectif du jour
        </h3>

        <p class="text-muted mb-0">
            Acquérir de nouvelles connaissances :

        </p>
        <p class="text-muted mb-0">
   
            Faire au moins une note sur chacune des matières suivantes 
        </p>

    </div>

    {{-- Barre de progression --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">

                <span class="fw-semibold">
                    Progression
                </span>

                <span class="fw-bold text-success">
                    {{ $pourcentage }} %
                </span>

            </div>

            <div class="progress" style="height:12px;">

                <div class="progress-bar bg-success"
                     role="progressbar"
                     style="width: {{ $pourcentage }}%;"
                     aria-valuenow="{{ $pourcentage }}"
                     aria-valuemin="0"
                     aria-valuemax="100">

                </div>

            </div>

            <small class="text-muted d-block mt-2">

                {{ $validees }} matière(s) validée(s)
                sur
                {{ $total }}

            </small>

            @if($pourcentage == 100 && $total > 0)

                <div class="alert alert-success mt-3 mb-0">
🎉 Excellent travail !
Vous avez atteint votre objectif quotidien d'apprentissage. Continuez sur cette lancée !

                </div>

            @endif

        </div>

    </div>

    {{-- Messages --}}
    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    {{-- Aucune matière --}}
    @if($emplois->isEmpty())

        <div class="alert alert-info text-center">

            <h5 class="mb-2">
                🎉 Aucun travail prévu aujourd'hui
            </h5>

            <p class="mb-0">
                Profitez-en pour avancer sur une autre matière.
            </p>

        </div>

    @else

        {{-- Liste des matières --}}

        @foreach($emplois as $emploi)

            <div class="card shadow-sm mb-3">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div class="flex-grow-1">

                            <a href="{{ route('chapitres.createForMatiere', $emploi->matiere_id) }}"
                               class="text-decoration-none">

                                <h5 class="text-primary mb-1">

                                    {{ $emploi->matiere->matiere }}

                                </h5>

                            </a>

                            @if($emploi->commentaire)

                                <p class="text-muted mb-0">

                                    {{ $emploi->commentaire }}

                                </p>

                            @endif

                        </div>

                        <div class="ms-3">

                            <form action="{{ route('emplois.validation', $emploi->id) }}"
                                  method="POST"
                                  onclick="event.stopPropagation();">

                                @csrf

                                <input
                                    type="checkbox"
                                    class="form-check-input fs-4"
                                    onchange="this.form.submit()"
                                    {{ $emploi->validations->isNotEmpty() ? 'checked' : '' }}>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    @endif

    <div class="mt-4 mb-5">

        <a href="{{ route('emplois.create') }}"
           class="btn btn-primary w-100">

            Gérer l'emploi du temps

        </a>

    </div>

    <div class="card shadow-sm mt-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-2">

            <span class="fw-semibold">
                Progression hebdomadaire
            </span>

            <span class="fw-bold text-primary">
                {{ $pourcentageHebdo }} %
            </span>

        </div>

        <div class="progress" style="height:12px;">

            <div class="progress-bar bg-primary"
                 style="width: {{ $pourcentageHebdo }}%;">

            </div>

        </div>

        <small class="text-muted d-block mt-2">

            {{ $valideesHebdo }} matière(s) validée(s)
            sur
            {{ $totalHebdo }}

        </small>

    </div>

</div>

</div>

@endsection
