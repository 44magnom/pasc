@extends('app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h4 class="mb-0">
            Emploi du temps
        </h4>

        <a href="{{ route('emplois.create') }}"
           class="btn btn-primary">
            + Ajouter
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle">

<thead class="table-dark">
    <tr>
        <th>Jour</th>
        <th>Matière</th>
        <th>Commentaire</th>
        <th class="text-center">Validée</th>
    </tr>
</thead>

<tbody>

    @forelse($emplois as $nomJour => $emploisDuJour)

        @foreach($emploisDuJour as $index => $emploi)

            <tr class="ligne-emploi"
                data-url="{{ route('chapitres.createForMatiere', $emploi->matiere_id) }}"
                style="cursor:pointer;">

                @if($index === 0)

                    <td rowspan="{{ $emploisDuJour->count() }}"
                        class="fw-bold text-center align-middle">

                        {{ $nomJour }}

                    </td>

                @endif

                <td>
                    {{ $emploi->matiere->matiere }}
                </td>

                <td>
                    {{ $emploi->commentaire ?? '-' }}
                </td>

<td class="text-center">

    <form action="{{ route('emplois.validation', $emploi->id) }}"
          method="POST">

        @csrf

        <input
            type="checkbox"
            class="form-check-input"
            onchange="this.form.submit()"
            {{ $emploi->validations->isNotEmpty() ? 'checked' : '' }}
        >

    </form>

</td>

            </tr>

        @endforeach

    @empty

        <tr>
            <td colspan="4" class="text-center text-muted">
                Aucun emploi du temps enregistré.
            </td>
        </tr>

    @endforelse

</tbody>
        </table>

    </div>

</div>

@endsection


