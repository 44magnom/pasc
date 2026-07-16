<div class="offcanvas offcanvas-start"
     tabindex="-1"
     id="offcanvasMatieres"
     aria-labelledby="offcanvasMatieresLabel">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasMatieresLabel">
            Mes matières
        </h5>
  <div class="d-flex align-items-center">

        <a href="{{ route('matieres.create') }}"
           class="btn btn-sm btn-primary me-2">
            <i class="bi bi-plus-lg"></i>
        </a>



    </div>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close">
        </button>
    </div>

    <div class="offcanvas-body">

        <div class="list-group">

            @foreach($matieres as $matiere)

                <a href="{{ route('chapitres.createForMatiere', $matiere->id) }}"
                   class="list-group-item list-group-item-action">

                    {{ $matiere->matiere }}

                    <span class="badge bg-primary float-end">
                        {{ $matiere->notes->count() }}
                    </span>

                </a>

            @endforeach

        </div>

    </div>

</div>