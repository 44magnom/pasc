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

<style>
    .nafar-card{
        background:#FFFDF9;
        border:2px solid #C8A97E;
        border-radius:18px;
    }

    .nafar-title{
        color:#654321;
    }

    .nafar-text{
        color:#7A6754;
    }

    .nafar-progress{
        height:14px;
        background:#F1E7D8;
        border-radius:50px;
    }

    .nafar-progress .progress-bar{
        background:linear-gradient(90deg,#8B5E3C,#C8A97E);
        border-radius:50px;
    }

    .nafar-btn{
        background:#8B5E3C;
        color:#fff;
        border:none;
    }

    .nafar-btn:hover{
        background:#6F472C;
        color:#fff;
    }

    .form-check-input:checked{
        background:#8B5E3C;
        border-color:#8B5E3C;
    }

    .matiere-card{
        background:#FFFDF9;
        border:2px solid #E6D3B3;
        border-radius:18px;
        transition:.25s;
    }

    .matiere-card:hover{
        transform:translateY(-2px);
        box-shadow:0 .5rem 1rem rgba(0,0,0,.10)!important;
    }

    .matiere-link{
        color:#654321;
        text-decoration:none;
    }

    .matiere-link:hover{
        color:#8B5E3C;
    }
</style>

<div class="container mt-4">

    {{-- Objectif --}}
    <div class="text-center mb-4">

        <h3 class="fw-bold mb-2 nafar-title">
            🎯 Objectif du jour
        </h3>

        <p class="mb-1 nafar-text">
            Acquérir de nouvelles connaissances.
        </p>

        <p class="mb-0 nafar-text">
            Faire au moins une note sur chacune des matières suivantes.
        </p>

    </div>

    {{-- Progression quotidienne --}}
    <div class="card nafar-card shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <span class="fw-semibold nafar-title">
                    Progression
                </span>

                <span class="fw-bold" style="color:#8B5E3C;">
                    {{ $pourcentage }} %
                </span>

            </div>

            <div class="progress nafar-progress">

                <div class="progress-bar"
                     style="width:{{ $pourcentage }}%;">

                </div>

            </div>

            <small class="d-block mt-3 nafar-text">

                <strong>{{ $validees }}</strong> matière(s) validée(s)
                sur
                <strong>{{ $total }}</strong>

            </small>

            @if($pourcentage == 100 && $total > 0)

                <div class="alert border-0 rounded-4 mt-4 mb-0"
                     style="background:#F8F2E8;color:#654321;border-left:5px solid #8B5E3C;">

                    <strong>🎉 Félicitations !</strong><br>

                    Vous avez atteint votre objectif quotidien d'apprentissage.
                    Continuez sur cette belle lancée !

                </div>

            @endif

        </div>

    </div>

    {{-- Message --}}
    @if(session('success'))

        <div class="alert border-0 shadow-sm rounded-4"
             style="background:#F8F2E8;color:#654321;border-left:5px solid #8B5E3C;">

            {{ session('success') }}

        </div>

    @endif

    {{-- Aucun emploi --}}
    @if($emplois->isEmpty())

        <div class="alert border-0 shadow-sm rounded-4 text-center"
             style="background:#FFF8EE;color:#654321;border-left:5px solid #C8A97E;">

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

            <div class="card matiere-card shadow-sm mb-3">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div class="flex-grow-1">

                            <a href="{{ route('chapitres.createForMatiere',$emploi->matiere_id) }}"
                               class="matiere-link">

                                <h5 class="fw-bold mb-1">

                                    {{ $emploi->matiere->matiere }}

                                </h5>

                            </a>

                            @if($emploi->commentaire)

                                <p class="mb-0 nafar-text">

                                    {{ $emploi->commentaire }}

                                </p>

                            @endif

                        </div>

                        <div class="ms-3">

                            <form action="{{ route('emplois.validation',$emploi->id) }}"
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

    {{-- Bouton --}}
    <div class="mt-4 mb-5">

        <a href="{{ route('emplois.create') }}"
           class="btn nafar-btn w-100">

            Gérer l'emploi du temps

        </a>

    </div>

    {{-- Progression hebdomadaire --}}
    <div class="card nafar-card shadow-sm mt-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <span class="fw-semibold nafar-title">
                    📅 Progression hebdomadaire
                </span>

                <span class="fw-bold" style="color:#8B5E3C;">
                    {{ $pourcentageHebdo }} %
                </span>

            </div>

            <div class="progress nafar-progress">

                <div class="progress-bar"
                     style="width:{{ $pourcentageHebdo }}%;">

                </div>

            </div>

            <small class="d-block mt-3 nafar-text">

                <strong>{{ $valideesHebdo }}</strong> matière(s) validée(s)
                sur
                <strong>{{ $totalHebdo }}</strong>

            </small>

        </div>

    </div>

</div>

@endsection