<div class="bottom-menu">

    <a href="#"
       class="menu-item"
       data-bs-toggle="offcanvas"
       data-bs-target="#offcanvasMatieres"
       aria-controls="offcanvasMatieres">

        <i class="bi bi-book"></i>

    </a>

    <a href="{{ route('accueil') }}"
       class="menu-item">

        <i class="bi bi-house"></i>

    </a>

   <a href="{{ route('dashboard') }}"
       class="menu-item">

        <i class="bi bi-person"></i>

    </a>

</div>

@push('styles')

<style>

.bottom-menu {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;

    width: 100%;
    height: 65px;

    background-color: #F3EBDD;

    display: flex;
    justify-content: space-around;
    align-items: center;

    border-top: 1px solid #E2D5C2;

    box-shadow: 0 -2px 10px rgba(0, 0, 0, .08);

    z-index: 9999;
}

.menu-item {
    flex: 1;

    display: flex;
    justify-content: center;
    align-items: center;

    text-decoration: none;

    color: #2F2A24;

    font-size: 24px;

    transition: .2s;
}

.menu-item:hover {
    color: #0d6efd;
}

body {
    padding-bottom: 75px;
}

</style>

@endpush