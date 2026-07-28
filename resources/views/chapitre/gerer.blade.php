@extends('app')

@section('content')

<div class="container mt-4">

    {{-- Titre --}}
    <div class="text-center mb-4">

        <h2 class="fw-bold" style="color:#654321;">
            📖 Gestion des chapitres
        </h2>
       

        <p style="color:#7A6754;">
            {{ $matiere->matiere }}
        </p>

    </div>

    {{-- Carte ajout --}}
    <div class="card shadow-sm mb-4"
         style="background:#FFFDF9;border:2px solid #C8A97E;border-radius:18px;">

        <div class="card-body">

            @if(session('success'))

                <div class="alert border-0 rounded-4"
                     style="background:#F8F2E8;color:#654321;border-left:5px solid #8B5E3C;">

                    {{ session('success') }}

                </div>

            @endif

            <form action="{{ route('chapitres.store') }}" method="POST">

                @csrf

                <input type="hidden"
                       name="matiere_id"
                       value="{{ $matiere->id }}">

                <div class="mb-3">

                    <label class="form-label fw-semibold"
                           style="color:#654321;">

                        Nom du chapitre

                    </label>

                    <input type="text"
                           name="chapitre"
                           value="{{ old('chapitre') }}"
                           class="form-control @error('chapitre') is-invalid @enderror"
                           placeholder="Ex : Les contrats de travail"
                           style="border:2px solid #E6D3B3;border-radius:10px;"
                           required>

                    @error('chapitre')

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
                        onmouseover="this.style.background='#8B5E3C';this.style.color='#fff';"
                        onmouseout="this.style.background='#F8F2E8';this.style.color='#654321';">

                    <i class="bi bi-plus-circle me-2"></i>

                    Ajouter le chapitre

                </button>

            </form>

        </div>

    </div>

    {{-- Liste --}}
    <div class="card shadow-sm"
         style="background:#FFFDF9;border:2px solid #C8A97E;border-radius:18px;">

        <div class="card-body">

            <h4 class="fw-bold mb-4"
                style="color:#654321;">

                📚 Liste des chapitres

            </h4>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead style="background:#8B5E3C;color:white;">

                        <tr>

                            <th width="70">#</th>

                            <th>Chapitre</th>

                            <th width="120" class="text-center">

                                Actions

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($chapitres as $chapitre)

                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>
<td style="font-weight:600;">
    <a href="{{ route('chapitres.show', $chapitre->id) }}"
       class="text-decoration-none"
       style="color:#654321;">
        {{ $chapitre->chapitre }}
    </a>
</td>

                                <td class="text-center">

                                    <div class="d-inline-flex gap-2">

                                        <a href="{{ route('chapitres.edit',$chapitre->id) }}"
                                           class="btn btn-sm"
                                           title="Modifier"
                                           style="
                                                background:#F8F2E8;
                                                color:#654321;
                                                border:2px solid #8B5E3C;
                                           ">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <form action="{{ route('chapitres.destroy',$chapitre->id) }}"
                                              method="POST"
                                              class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm"
                                                    title="Supprimer"
                                                    style="
                                                        background:#A94438;
                                                        color:white;
                                                        border:none;
                                                    "
                                                    onclick="return confirm('Voulez-vous vraiment supprimer ce chapitre ?')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3"
                                    class="text-center py-4"
                                    style="color:#7A6754;">

                                    Aucun chapitre enregistré.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- Retour --}}
    <div class="mt-4">

        <a href="{{ route('matieres.create') }}"
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

            <i class="bi bi-arrow-left-circle me-2"></i>

            Retour aux matières

        </a>

    </div>

</div>

@endsection