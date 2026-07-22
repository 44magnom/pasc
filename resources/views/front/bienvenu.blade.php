@extends('app')

@section('content')

<div class="container-fluid py-5" style="background-color:#F8F4EC; min-height:100vh;">

    <div class="row justify-content-center">

        <div class="col-lg-8 col-xl-7">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- En-tête -->
                <div class="text-center py-5" style="background-color:#EFE5D7;">

                    <h1 class="display-4 fw-bold mb-3" style="color:#654321;">
                        NafarBox
                    </h1>

                    <p class="fs-5 mb-0" style="color:#5E5145;">
                        La plateforme qui vous accompagne vers l'excellence.
                    </p>

                </div>

                <!-- Corps -->
   <!-- Corps -->
<div class="card-body p-0" style="background:#FFFDF9;">
<div>


<img src="{{ asset('images/box1.jpg') }}"
     alt="Nafarbox"
     class="w-100 d-block">
    <div class="p-5 text-center">

        <!-- Icône -->
        <i class="bi bi-stars d-block mb-4"
           style="font-size:50px; color:#654321;"></i>

        <h2 class="fw-bold mb-4" style="color:#654321;">
            Devenez chaque jour une meilleure version de vous-même.
        </h2>

        <p class="lead text-muted mb-4">
            NafarBox est une plateforme conçue pour vous aider à apprendre,
            mémoriser et progresser durablement.
        </p>

        <p class="text-muted mb-5">
            Préparez vos examens, concours et certifications grâce
            à la méthode de révision espacée.
        </p>

        <div class="d-grid gap-3 col-md-7 mx-auto">

            <a href="{{ route('register') }}"
               class="btn btn-lg text-white fw-semibold"
               style="background:#654321;">
                <i class="bi bi-person-plus-fill me-2"></i>
                Créer un compte
            </a>

            <a href="{{ route('login') }}"
               class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                J'ai déjà un compte
            </a>

        </div>

    </div>

</div>

                <!-- Pied -->
                <div class="text-center py-3"
                     style="background:#F4EEE5;">

                    <small class="text-muted">

                        © {{ date('Y') }} NafarBox • Apprendre aujourd'hui, réussir demain.

                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection