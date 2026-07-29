@extends('app')

@section('content')

<style>
    :root{
        --bg:#F8F4EC;
        --card:#FFFFFF;
        --border:#F3EBDD;
        --brown:#654321;
        --text:#654321;
        --text-light:#8A7A6A;
        --green:#198754;
        --blue:#0d6efd;
        --red:#dc3545;
    }

    body{
        background:var(--bg);
    }

    .welcome-card{
        background:linear-gradient(135deg,#ffffff,#fbf7f1);
        border:1px solid var(--border);
        border-radius:22px;
        box-shadow:0 8px 25px rgba(0,0,0,.05);
    }

    .resume-box{
        background:#fff;
        border:1px solid var(--border);
        border-radius:16px;
        padding:20px;
        transition:.3s;
    }

    .resume-box:hover{
        transform:translateY(-4px);
        box-shadow:0 8px 20px rgba(0,0,0,.05);
    }

    .icon-circle{
        width:75px;
        height:75px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:auto;
        font-size:32px;
    }

    .btn-round{
        border-radius:50px;
        padding:12px 30px;
        font-weight:600;
    }

    .rule-card{
        background:#fff;
        border:1px solid var(--border);
        border-radius:20px;
    }

    .progress{
        height:10px;
        border-radius:50px;
    }

    h2,h3,h4,h5{
        color:var(--brown);
    }

    .text-brown{
        color:var(--brown);
    }
</style>

<div class="container py-5">

    {{-- Bienvenue --}}
    <div class="welcome-card p-4 mb-5">

        <div class="row align-items-center">

<div class="col-lg-8 d-flex flex-column justify-content-center text-center">

    <h2 class="fw-bold mb-2">
        NafarBox.com
    </h2>

    <p class="text-muted mb-4">
        Votre compagnon vers l'excellence !
    </p>

    @php
        $total = $notesAReviser + $notesEnRetard;
        $pourcentage = $total > 0 ? round((($notesAReviser)/$total)*100) : 100;
    @endphp

</div>

<div class="col-6">

    <a href="{{ route('revision2.anciennes') }}"
       class="text-decoration-none">

        <div class="resume-box text-center">

            <i class="bi bi-alarm-fill text-danger fs-2"></i>

            <h3 class="fw-bold mt-2 text-dark">
                {{ $notesEnRetard }}
            </h3>

            <small class="text-muted">
                En retard
            </small>

        </div>

    </a>

</div>
<div class="col-6">

    <a href="{{ route('revision.jour') }}"
       class="text-decoration-none">

        <div class="resume-box text-center">

            <i class="bi bi-calendar-check-fill text-primary fs-2"></i>

            <h3 class="fw-bold mt-2 text-dark">
                {{ $notesAReviser }}
            </h3>

            <small class="text-muted">
                Aujourd'hui
            </small>

        </div>

    </a>

</div>

        </div>

    </div>

    {{-- Cartes --}}
    <div class="row g-4">



 


    </div>

    {{-- Félicitations --}}
    @if($notesAReviser==0 && $notesEnRetard==0)

        <div class="rule-card mt-5">

            <div class="card-body text-center py-5">

                <i class="bi bi-patch-check-fill text-success display-1"></i>

                <h2 class="fw-bold mt-4">

                    Félicitations !

                </h2>

                <p class="text-muted">

                    Toutes vos révisions sont terminées.

                </p>

                <a href="{{ route('emplois.index') }}"
                   class="btn btn-success btn-round mt-2">

                    <i class="bi bi-bullseye"></i>

                    Passer à l'objectif du jour

                </a>

            </div>

        </div>

    @endif

    {{-- Citation --}}
    <div class="rule-card mt-5">

        <div class="card-body text-center py-4">

            <i class="bi bi-lightbulb-fill text-warning display-6"></i>

            <h4 class="fw-bold mt-3">

                Règle d'or

            </h4>

            <p class="text-muted mb-0">

                <strong>Révise toujours tes fiches avant d'en créer de nouvelles.</strong>

                <br>

                Une petite révision aujourd'hui vaut mieux qu'une longue révision demain.

            </p>

        </div>

    </div>

</div>

@endsection