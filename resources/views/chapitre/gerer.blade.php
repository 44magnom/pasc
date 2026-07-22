@extends('app')

@section('content')
<div class="container">

    <div class="card shadow-sm mb-4"
         style="background-color:#F8F3EB; border:1px solid #D2B48C;">

        <div class="card-body text-center">

            <h4 class="mb-0" style="color:#654321;">
                {{ $matiere->matiere }}
            </h4>

        </div>

    </div>

    <table class="table table-bordered table-hover align-middle">

        <thead>
            <tr>
                <th width="50">
                    <input type="checkbox" id="checkAll">
                </th>
                <th>Chapitre</th>
                <th width="180" class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>

            @foreach($chapitres as $chapitre)

                <tr>

                    <td class="text-center">
                        <input type="checkbox" name="chapitres[]" value="{{ $chapitre->id }}">
                    </td>

                    <td>
                        {{ $chapitre->chapitre }}
                    </td>

                    <td class="text-center">

                        <a href="{{ route('chapitres.edit',$chapitre->id) }}"
                           class="btn btn-sm btn-beige">
                            Modifier
                        </a>

                        <form action="{{ route('chapitres.destroy',$chapitre->id) }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger">
                                Supprimer
                            </button>

                        </form>

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>
@endsection