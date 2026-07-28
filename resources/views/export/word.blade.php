@extends('app')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">
        <i class="bi bi-file-earmark-word"></i>
        Export de toutes les notes
    </h2>

    @foreach($matieres as $matiere)

        <h3 class="mt-5 text-primary">
            {{ $matiere->matiere }}
        </h3>

        @foreach($matiere->chapitres as $chapitre)

            <h4 class="mt-4">
                {{ $chapitre->chapitre }}
            </h4>

            @foreach($chapitre->notes as $note)

                <div class="card mb-3">
                    <div class="card-body">

                        <h6 class="fw-bold">Question</h6>

                        {!! $note->recto !!}

                        <hr>

                        <h6 class="fw-bold">Réponse</h6>

                        {!! $note->verso !!}

                    </div>
                </div>

            @endforeach

        @endforeach

    @endforeach

</div>

@endsection