@extends('app')

@section('content')

<div class="container mt-5">

    <div class="text-center mb-5">
<p>
    Notes à réviser aujourd'hui :
    <strong>{{ $notesAReviser }}</strong>
</p>
    </div>
    <div class="text-center mb-5">

        <p class="text-muted">Que souhaitez-vous faire ?</p>
    </div>

    <div class="row justify-content-center">

        <div class="col-md-4 mb-3">
            <a href="{{ route('revision.generale') }}"
               class="btn btn-primary btn-lg w-100 py-4">
                📚<br>
                Réviser
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="{{ route('notes.create') }}"
               class="btn btn-success btn-lg w-100 py-4">
                ➕<br>
                Ajouter une note
            </a>
        </div>

    </div>

</div>

@endsection