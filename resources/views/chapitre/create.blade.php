@extends('app')

@section('content')

<div class="container mt-4">

    <!-- Bannière -->
    <div class="card bg-primary text-white shadow mb-4">

 <div class="card-body text-center">

    <h3 class="mb-0">
        {{ $matiere->matiere }}
    </h3>

</div>
        
    </div>

    <!-- Accordéon -->
    <div class="accordion mb-4" id="accordionChapitre">

        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#ajoutChapitre">

                    ➕ Ajouter un chapitre

                </button>

            </h2>

            <div id="ajoutChapitre"
                 class="accordion-collapse collapse">

                <div class="accordion-body">

                    <form action="{{ route('chapitres.store') }}"
                          method="POST">

                        @csrf

                        <input type="hidden"
                               name="matiere_id"
                               value="{{ $matiere->id }}">

                        <div class="mb-3">

                            <input
                                type="text"
                                class="form-control"
                                name="chapitre"
                                placeholder="Nom du chapitre"
                                required>

                        </div>

                        <button class="btn btn-primary">
                            Enregistrer
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    
    <div>
    <a href="{{ route('revision.show', $matiere->id) }}"
       class="btn btn-light w-100">
        🃏 Réviser tous les chapitres
    </a>
</div>

    <!-- Liste des chapitres -->

    <h5>Chapitres</h5>

    @forelse($matiere->chapitres as $chapitre)

      <a href="{{ route('chapitres.show', $chapitre->id) }}"
   class="text-decoration-none d-block">

    <div class="card mb-3 shadow-sm">

        <div class="card-body">

            <strong class="text-primary">
                {{ $chapitre->chapitre }}
            </strong>

            <br>

            <small class="text-muted">
                {{ $chapitre->notes->count() }} notes
            </small>

        </div>

    </div>

</a>

    @empty

        <div class="alert alert-info">
            Aucun chapitre.
        </div>

    @endforelse

</div>

@endsection