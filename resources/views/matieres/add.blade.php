@extends('app')

@section('content')

<style>
    .nafar-card{
        background:#FFFDF9;
        border:2px solid #C8A97E;
        border-radius:18px;
    }

    .nafar-title{
        color:#654321;
    }

    .nafar-text{
        color:#7A6754;
    }

    .nafar-btn{
        background:#8B5E3C;
        color:#fff;
        border:none;
        border-radius:10px;
    }

    .nafar-btn:hover{
        background:#6F472C;
        color:#fff;
    }

    .nafar-input{
        border:2px solid #E6D3B3;
        border-radius:10px;
    }

    .nafar-input:focus{
        border-color:#8B5E3C;
        box-shadow:0 0 0 .2rem rgba(139,94,60,.15);
    }

    .table-nafar thead{
        background:#8B5E3C;
        color:#fff;
    }

    .table-nafar tbody tr:hover{
        background:#F8F2E8;
    }

    .matiere-link{
        text-decoration:none;
        color:#654321;
        font-weight:600;
    }

    .matiere-link:hover{
        color:#8B5E3C;
    }

    .btn-edit{
        background:#C8A97E;
        color:#fff;
        border:none;
    }

    .btn-edit:hover{
        background:#B08D5A;
        color:#fff;
    }

    .btn-delete{
        background:#A94438;
        color:#fff;
        border:none;
    }

    .btn-delete:hover{
        background:#8A362C;
        color:#fff;
    }
</style>

<div class="container mt-4">

    {{-- Titre --}}
    <div class="text-center mb-4">
@if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Limite atteinte !</strong>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
        <h2 class="fw-bold nafar-title">
            📚 Gestion des matières
        </h2>

        <p class="nafar-text">
            Ajoutez et organisez les matières de votre espace d'apprentissage.
        </p>

    </div>

    {{-- Formulaire --}}
    <div class="card nafar-card shadow-sm mb-4">

        <div class="card-body">

            @if(session('success'))

                <div class="alert border-0 rounded-4"
                     style="background:#F8F2E8;color:#654321;border-left:5px solid #8B5E3C;">

                    {{ session('success') }}

                </div>

            @endif

            <form action="{{ route('matieres.store') }}" method="POST">

                @csrf

                <div class="mb-3">

                    <label for="matiere"
                           class="form-label fw-semibold nafar-title">

                        Nom de la matière

                    </label>

                    <input
                        type="text"
                        id="matiere"
                        name="matiere"
                        value="{{ old('matiere') }}"
                        class="form-control nafar-input @error('matiere') is-invalid @enderror"
                        placeholder="Ex : Droit du travail"
                        required>

                    @error('matiere')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

<button type="submit"
        class="btn w-100"
        style="
            background:#F8F2E8;
            color:#654321;
            border:2px solid #8B5E3C;
            border-radius:12px;
            font-weight:600;
            padding:12px;
        "
        onmouseover="this.style.background='#8B5E3C'; this.style.color='#fff';"
        onmouseout="this.style.background='#F8F2E8'; this.style.color='#654321';">

    <i class="bi bi-plus-circle me-2"></i>
    Ajouter la matière

</button>

            </form>

        </div>

    </div>
    
<div>
    <a href="{{ route('dashboard') }}"
   class="btn w-100 mb-3"
   style="
        background:#FFFDF9;
        color:#654321;
        border:2px solid #8B5E3C;
        border-radius:12px;
        font-weight:600;
        padding:12px;
   "
   onmouseover="this.style.background='#8B5E3C'; this.style.color='#fff';"
   onmouseout="this.style.background='#FFFDF9'; this.style.color='#654321';">

    <i class="bi bi-arrow-left-circle me-2"></i>
    Retour au tableau de bord

</a>

</div>
    {{-- Liste --}}
    <div class="card nafar-card shadow-sm">

        <div class="card-body">

            <h4 class="fw-bold nafar-title mb-4">

                📖 Liste des matières

            </h4>

            <div class="table-responsive">

                <table class="table align-middle table-hover table-nafar">

                    <thead>

                        <tr>

                            <th width="70">#</th>

                            <th>Matière</th>

<th class="text-center">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($matieres as $matiere)

                            <tr>

<td>{{ $loop->iteration }}</td>

                                <td>

                                    <a href="{{ route('chapitres.createForMatiere',$matiere->id) }}"
                                       class="matiere-link">

                                        {{ $matiere->matiere }}

                                    </a>

                                </td>

      <td class="text-center">

    <div class="d-inline-flex gap-2">

        <a href="{{ route('matieres.edit', $matiere->id) }}"
           class="btn btn-edit btn-sm"
           title="Modifier">

            <i class="bi bi-pencil-square"></i>

        </a>

        <form action="{{ route('matieres.destroy', $matiere->id) }}"
              method="POST"
              class="d-inline">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-delete btn-sm"
                    title="Supprimer"
                    onclick="return confirm('Voulez-vous vraiment supprimer cette matière ?')">

                <i class="bi bi-trash"></i>

            </button>

        </form>

    </div>

</td>
                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="text-center py-4 nafar-text">

                                    Aucune matière enregistrée.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>

    <div class="mt-4">

        <a href="{{ route('export.txt') }}"
           class="btn w-100"
           style="
                background:#FFFDF9;
                color:#654321;
                border:2px solid #8B5E3C;
                border-radius:12px;
                font-weight:600;
                padding:12px;
           "
           onmouseover="this.style.background='#8B5E3C';this.style.color='#fff';"
           onmouseout="this.style.background='#FFFDF9';this.style.color='#654321';">

        <i class="bi bi-download me-1"></i>

          Exporter toutes les matières

        </a>

    </div>
@endsection