<nav class="navbar navbar-expand-lg navbar-pasc">

    <div class="container-fluid">

<a class="navbar-brand" href="{{ route('accueil') }}" style="color:#654321;">
    NafarBox
</a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse"
             id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">

                    <a class="nav-link active"
                       href="{{ route('accueil') }}">
                        Accueil
                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link"
                       href="{{ route('emplois.index') }}">
                        Objectif du jour
                    </a>

                </li>
                <li class="nav-item">

                    <a class="nav-link"
                       href="{{ route('notes.create') }}">
                        Ajouter une note
                    </a>

                </li>

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">

                        Matières

                    </a>

                    </li>
                    @auth
    <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="nav-link btn btn-link p-0 border-0">
                Déconnexion
            </button>
        </form>
    </li>
@endauth

@guest
    <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">
            Connexion
        </a>
    </li>
@endguest

                    <ul class="dropdown-menu">

                        <li>
                            <a class="dropdown-item"
                               href="{{ route('matieres.index') }}">
                                Liste matières
                            </a>
                        </li>

                    </ul>

                </li>

            </ul>

            <form class="d-flex"
                  role="search">

                <input class="form-control me-2"
                       type="search"
                       placeholder="Rechercher">

                <button class="btn btn-outline-dark"
                        type="submit">
                    Rechercher
                </button>

            </form>

        </div>

    </div>

</nav>


@push('styles')

<style>

.navbar-pasc {
    background-color: #F3EBDD;
    border-bottom: 1px solid #E2D5C2;
}

.navbar-pasc .navbar-brand {
    color: #2F2A24;
    font-weight: 700;
}

.navbar-pasc .nav-link {
    color: #4A4036;
}

.navbar-pasc .nav-link:hover {
    color: #0d6efd;
}

.navbar-pasc .nav-link.active {
    color: #2F2A24;
    font-weight: 600;
}

.navbar-pasc .dropdown-menu {
    background-color: #FFF9ED;
    border-color: #E2D5C2;
}

.navbar-pasc .dropdown-item:hover {
    background-color: #F3EBDD;
}

</style>

@endpush