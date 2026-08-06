@extends('app')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card shadow">

                <div class="card-header text-center"
                     style="background:#F8F3EB;color:#654321;">

                    <h3 class="mb-0">
                        Attribution manuelle d'un abonnement
                    </h3>

                </div>

                <div class="card-body">

                    <form action="{{ route('abonnement.manuel.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">
                                Utilisateur
                            </label>

                            <select name="user_id" class="form-select" required>

                                <option value="">Choisir un utilisateur</option>

                                @foreach($users as $user)

                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Forfait
                            </label>

                            <select name="forfait_id" class="form-select" required>

                                <option value="">Choisir un forfait</option>

                                @foreach($forfaits as $forfait)

                                    <option value="{{ $forfait->id }}">
                                        {{ $forfait->nom }}
                                        -
                                        {{ $forfait->montant }} FCFA
                                        -
                                        {{ $forfait->duree }} jours
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <button class="btn btn-success w-100">

                            Activer l'abonnement

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection