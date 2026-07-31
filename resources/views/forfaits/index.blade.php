@extends('app')

@section('content')

<div class="container py-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color:#654321;">
            Choisissez votre forfait
        </h2>

        <p class="text-muted">
            Débloquez toutes les fonctionnalités de NafarBox et révisez sans aucune limite.
        </p>
    </div>

    <div class="row justify-content-center">
@if($abonnement)

<div class="alert alert-success shadow-sm border-0 mb-5">

    <h5 class="fw-bold">
        <i class="bi bi-patch-check-fill"></i>
        Votre abonnement actuel
    </h5>

    <hr>

    <div class="row">

        <div class="col-md-3">
            <strong>Forfait</strong><br>
            {{ $abonnement->forfait->nom }}
        </div>

        <div class="col-md-3">
            <strong>Début</strong><br>
            {{ \Carbon\Carbon::parse($abonnement->date_debut)->format('d/m/Y') }}
        </div>

        <div class="col-md-3">
            <strong>Expiration</strong><br>
            {{ \Carbon\Carbon::parse($abonnement->date_fin)->format('d/m/Y') }}
        </div>

        <div class="col-md-3">
            <strong>Jours restants</strong><br>

            {{ now()->diffInDays($abonnement->date_fin, false) }}
            jour(s)

        </div>

    </div>

</div>

@else

<div class="alert alert-warning shadow-sm border-0 mb-5">

    <h5 class="fw-bold">
        <i class="bi bi-exclamation-circle"></i>
        Aucun abonnement actif
    </h5>

    <p class="mb-0">
        Vous utilisez actuellement la version gratuite de NafarBox.
        Choisissez un forfait ci-dessous pour débloquer toutes les fonctionnalités.
    </p>

</div>

@endif
        @foreach($forfaits as $forfait)

            <div class="col-md-4 mb-4">

                <div class="card border-0 shadow h-100">

                    <div class="card-body text-center">

                        <h4 class="fw-bold text-success">
                            {{ $forfait->nom }}
                        </h4>

                        <h1 class="display-5 fw-bold my-4">
                            {{ number_format($forfait->montant,0,' ',' ') }}
                            <small class="fs-5">FCFA</small>
                        </h1>

                        <p class="text-muted">
                            Durée :
                            <strong>{{ $forfait->duree }} jours</strong>
                        </p>

                        <hr>

                        <p>
                            {{ $forfait->description }}
                        </p>

                        <ul class="list-unstyled text-start mt-4">

                            <li class="mb-2">
                                ✅ Matières illimitées
                            </li>

                            <li class="mb-2">
                                ✅ Chapitres illimités
                            </li>

                            <li class="mb-2">
                                ✅ Notes illimitées
                            </li>

                            <li class="mb-2">
                                ✅ Export PDF / Word
                            </li>

                            <li class="mb-2">
                                ✅ Sauvegarde sécurisée
                            </li>

                        </ul>

                    </div>

                    <div class="card-footer bg-white border-0">

                        <div class="d-grid">

                            <a href="{{ route('forfaits.show',$forfait->id) }}"
                               class="btn btn-success btn-lg">

                                S'abonner

                            </a>

                                        <a href="{{ route('paydunya.payment', ['id_forfait' => $forfait->id]) }}" class="btn btn-success">
                S'abonner à ce forfait
            </a>


                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection