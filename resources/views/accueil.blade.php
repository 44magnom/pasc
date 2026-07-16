@extends('app')

@section('content')

<div class="container mt-5">

    <div class="text-center mb-5">

        <p>
            Notes à réviser aujourd'hui :
            <strong>{{ $notesAReviser }}</strong>
        </p>

    </div>



    <div class="row justify-content-center">

@if($notesAReviser > 0)

    <div class="col-md-4 mb-3">

        <a href="{{ route('revision.generale') }}"
           class="btn btn-primary btn-lg w-100 py-4">

            📚<br>
            Réviser

        </a>

    </div>

@endif

        <div class="col-md-4 mb-3">

            <a href="{{ route('emplois.index') }}"
               class="btn btn-warning btn-lg w-100 py-4">

                🎯<br>
                Objectif du jour

            </a>

        </div>

    </div>

</div>

@endsection