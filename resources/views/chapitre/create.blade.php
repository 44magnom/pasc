@extends('app')

@section('content')

<div class="container mt-4">

    <!-- Bannière -->
<div class="card shadow mb-4"
     style="background-color:#F8F3EB; color:#3E3E3E; border:1px solid #8B6B4A;">

 <div class="card-body text-center" style="color:#654321;">

    <h3 class="mb-0">
        {{ $matiere->matiere }}
    </h3>

</div>
        
    </div>

<!-- Accordéon -->
<div class="accordion mb-4" id="accordionChapitre">

    <div class="accordion-item border-0 shadow-sm">

        <h2 class="accordion-header">

            <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#ajoutChapitre"
                    style="background-color:#F8F3EB; color:#654321;">

                <i class="bi bi-plus-circle me-2"></i>
                Ajouter un chapitre

            </button>

        </h2>

        <div id="ajoutChapitre"
             class="accordion-collapse collapse">

            <div class="accordion-body"
                 style="background-color:#FDFBF8;">

                <form action="{{ route('chapitres.store') }}"
                      method="POST">

                    @csrf

                    <input type="hidden"
                           name="matiere_id"
                           value="{{ $matiere->id }}">

                    <div class="mb-3">

                        <input type="text"
                               class="form-control"
                               name="chapitre"
                               placeholder="Nom du chapitre"
                               style="border:1px solid #D2B48C;"
                               required>

                    </div>

                    <button type="submit"
                            class="btn"
                            style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">
                        <i class="bi bi-check-circle me-1"></i>
                        Enregistrer
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

    
    <div>
<a href="{{ route('revision.show', $matiere->id) }}"
   class="btn w-100"
   style="background-color:#F8F3EB; color:#654321; border:1px solid #D2B48C;">
    <i class="bi bi-stack me-2"></i>
    Réviser tous les chapitres
</a>
</div>

<h6 class="fw-semibold  mt-3" style="color:#654321;">Chapitres:</h6>
    @forelse($matiere->chapitres as $chapitre)

      <a href="{{ route('chapitres.show', $chapitre->id) }}"
   class="text-decoration-none d-block">

    <div class="card mb-3 shadow-sm">

        <div class="card-body">

<span class="fw-semibold" style="color:#654321;">
    {{ $chapitre->chapitre }}
</span>

            <br>

            <small class="text-muted">
                {{ $chapitre->notes->count() }} notes
            </small>

        </div>

    </div>

</a>

    @empty

        <div class="alert border-0"
     style="background-color:#F8F3EB; color:#6B5B4B;">
            Aucun chapitre.
        </div>

    @endforelse

</div>

@endsection