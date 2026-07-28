<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">

        @if(Route::is('accueil'))
            <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>

        @elseif(Route::is('dashboard'))
            <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>
            <li class="breadcrumb-item active">
                Matières
            </li>

        @elseif(Route::is('chapitres.createForMatiere'))
            <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>
                        <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"> matières</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('chapitres.createForMatiere',$matiere->id) }}">  {{ $matiere->matiere }}</a>
            </li>

            <li class="breadcrumb-item active">
                 Chapitres
            </li>

        @elseif(Route::is('chapitres.show'))
                      <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>
                        <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"> matières</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('chapitres.createForMatiere',$chapitre->matiere->id) }}">   {{ $chapitre->matiere->matiere }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('chapitres.show', $chapitre->id) }}">        {{ $chapitre->chapitre }}</a>
            </li>

            <li class="breadcrumb-item active">
                 notes
            </li>


        @elseif(Route::is('notes.creates'))
                      <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>
                        <li class="breadcrumb-item">
                <a href="{{ route('matieres.index') }}"> matières</a>
            </li>
<li class="breadcrumb-item">
    <a href="{{ route('chapitres.index', $chapitre->matiere->id) }}">
        {{ $chapitre->matiere->matiere }}
    </a>
</li>

<li class="breadcrumb-item">
    <a href="{{ route('chapitres.show', $chapitre->id) }}">
        {{ $chapitre->chapitre }}
    </a>
</li>


            <li class="breadcrumb-item active">
               Ajouter note
            </li>
        @elseif(Route::is('chapitres.gerernote'))
                      <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>
                        <li class="breadcrumb-item">
                <a href="{{ route('matieres.index') }}"> matières</a>
            </li>
<li class="breadcrumb-item">
    <a href="{{ route('chapitres.index', $chapitre->id) }}">
        {{ $chapitre->matiere->matiere }}
    </a>
</li>

<li class="breadcrumb-item">
    <a href="{{ route('chapitres.show', $chapitre->id) }}">
        {{ $chapitre->chapitre }}
    </a>
</li>


            <li class="breadcrumb-item active">
               Gérer les notes
            </li>
        @elseif(Route::is('notes.edit'))
                      <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>
                        <li class="breadcrumb-item">
                <a href="{{ route('matieres.index') }}"> matières</a>
            </li>
<li class="breadcrumb-item">
    <a href="{{ route('chapitres.index', $note->chapitre->matiere_id) }}">
        {{ $note->chapitre->matiere->matiere }}
    </a>
</li>

<li class="breadcrumb-item">
    <a href="{{ route('chapitres.show', $note->chapitre_id) }}">
        {{ $note->chapitre->chapitre }}
    </a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('chapitres.show', $note->chapitre_id) }}">
          notes
    </a>
</li>

            <li class="breadcrumb-item active">
               Modififer
            </li>

        @elseif(Route::is('revision2.anciennes'))
            <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>
            <li class="breadcrumb-item active">
                Notes en retard
            </li>
        @elseif(Route::is('revision.jour'))
            <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>
            <li class="breadcrumb-item active">
                Notes  du jour
            </li>

        @elseif(Route::is('emplois.index'))
            <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}">Accueil</a>
            </li>
            <li class="breadcrumb-item active">
                Objectif du jour
            </li>

        @endif

    </ol>
</nav>