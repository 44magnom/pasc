@extends('app')

@section('content')

<div class="container mt-5">

    {{-- Résumé --}}
    <div class="mb-4 text-muted text-center">

        <p class="mb-1">
            Notes du jour :
            <span>{{ $notesAReviser }}</span>
        </p>

        <p class="mb-0">
            Notes en retard non validées :
            <span>{{ $notesEnRetard }}</span>
        </p>

    </div>

    <div class="row justify-content-center">
        {{-- Notes en retard --}}
        @if($notesEnRetard > 0)
        <div class="col-md-4 mb-3">

            <a href="{{ route('revision.anciennes') }}"
               class="btn btn-outline-danger btn-lg w-100 py-3 rounded-pill">

                Réviser les notes en retard

            </a>

        </div>
        @endif
        {{-- Notes du jour --}}
        @if($notesAReviser > 0)
        <div class="col-md-4 mb-3">

            <a href="{{ route('revision.jour') }}"
               class="btn btn-outline-primary btn-lg w-100 py-3 rounded-pill">

                Réviser les notes du jour

            </a>

        </div>
        @endif



        {{-- Objectif du jour --}}
        <div class="col-md-4 mb-3">

<a href="{{ route('emplois.index') }}"
   class="btn btn-outline-success btn-lg w-100 py-3 rounded-pill">

    🎯 Objectif du jour

</a>

        </div>

    </div>

    {{-- Message si aucune révision --}}
    @if($notesAReviser == 0 && $notesEnRetard == 0)

        <div class="alert alert-success text-center mt-4 rounded-3">
            🎉 Félicitations ! Vous êtes à jour. Aucune note à réviser aujourd'hui.
        </div>

    @endif

</div>

@endsection