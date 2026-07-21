@extends('app')

@section('content')

<div class="container-fluid py-5" style="background-color:#F8F4EC; min-height:100vh;">

    <div class="row justify-content-center">

        <div class="col-lg-8 col-xl-7">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- En-tête -->
                <div class="text-center py-5" style="background-color:#EFE5D7;">

                    <h1 class="display-4 fw-bold mb-3" style="color:#5A3E85;">
                        NafarBox
                    </h1>

                    <p class="fs-5 mb-0" style="color:#5E5145;">
                        La plateforme qui vous accompagne vers votre meilleure version.
                    </p>

                </div>

                <!-- Corps -->
                <div class="card-body p-5 text-center" style="background:#FFFDF9;">

                    <i class="bi bi-stars mb-4"
                       style="font-size:70px;color:#6F42C1;"></i>

                    <h2 class="fw-bold mb-4" style="color:#4A3F35;">
                        Devenez chaque jour une meilleure version de vous-même.
                    </h2>

                    <p class="lead text-muted mb-4">

                        NafarBox est une plateforme conçue pour vous aider à apprendre,
                        mémoriser et progresser durablement dans tous les domaines
                        dans lesquels vous souhaitez vous développer.

                    </p>

                    <p class="text-muted mb-5">

                        Que vous prépariez un examen, un concours, une certification,
                        ou que vous souhaitiez simplement acquérir de nouvelles
                        compétences, NafarBox vous aide à organiser vos connaissances,
                        à les réviser au bon moment et à progresser continuellement.

                    </p>

                    <div class="d-grid gap-3 col-md-7 mx-auto">

                        <a href="{{ route('register') }}"
                           class="btn btn-lg text-white fw-semibold"
                           style="background-color:#6F42C1;">

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