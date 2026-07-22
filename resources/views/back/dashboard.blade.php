@extends('app')

@section('content')

<div class="container mt-4">

    <!-- Bannière -->
<div class="card shadow-sm mb-4"
     style="background-color:#F8F3EB; border:1px solid #D2B48C;">

    <div class="card-body text-center">

        <div class="d-flex justify-content-center align-items-center">

            <h3 class="mb-0 me-2 fw-semibold" style="color:#654321;">
                Liste des matières
            </h3>

            <a href="{{ route('matieres.create') }}"
               style="color:#654321;"
               title="Ajouter une matière">

        <i class="bi bi-gear-fill fs-4"></i>

            </a>

        </div>

    </div>

</div>

    <!-- Accordéon -->

    <div class="accordion mb-4" id="accordionMatiere">

        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#ajoutMatiere">

                    ➕ Ajouter une matière

                </button>

            </h2>

            <div id="ajoutMatiere"
     class="accordion-collapse collapse">

    <div class="accordion-body"
         style="background-color:#FDFBF8;">

        <form action="{{ route('matieres.store') }}"
              method="POST">

            @csrf

            <div class="mb-3">

                <input type="text"
                       class="form-control"
                       name="matiere"
                       placeholder="Nom de la matière"
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

    <!-- Recherche -->

    <!-- <div class="mb-4">

        <input type="text"
               id="searchMatiere"
               class="form-control"
               placeholder="🔍 Rechercher une matière...">

    </div> -->

    <!-- Liste -->
<h6 class="fw-semibold text-secondary mb-3">Mes matières</h6>

    @forelse($matieres as $matiere)

        <a href="{{ route('chapitres.createForMatiere',$matiere->id) }}"
           class="text-decoration-none d-block matiere-card">

            <div class="card mb-3 shadow-sm">

                <div class="card-body">

                  <strong class="matiere-name" style="color:#654321;">

                        {{ $matiere->matiere }}

                    </strong>

                    <br>

                    <small class="text-muted">

                        {{ $matiere->chapitres->count() }}
                        chapitres

                        •

                        {{ $matiere->notes->count() }}
                        notes

                    </small>

                </div>

            </div>

        </a>

    @empty

        <div class="alert alert-info">

            Aucune matière.

        </div>

    @endforelse

</div>

@endsection
@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('searchMatiere');

    input.addEventListener('input', function () {

        let recherche = this.value.toLowerCase().trim();

        document.querySelectorAll('.matiere-card').forEach(function (card) {

            let nom = card.querySelector('.matiere-name')
                          .textContent
                          .toLowerCase();

            if (nom.includes(recherche)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }

        });

    });

});

</script>
@endpush