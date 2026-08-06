@extends('app')

@section('content')

<style>
:root{
    --bg:#F8F4EC;
    --card:#FFFFFF;
    --border:#F3EBDD;
    --brown:#654321;
    --brown-light:#8A7A6A;
    --green:#198754;
}

body{
    background:var(--bg);
}

.page-title{
    color:var(--brown);
    font-weight:700;
}

.hero{
    background:linear-gradient(135deg,#ffffff,#F8F4EC);
    border:1px solid var(--border);
    border-radius:22px;
    padding:35px;
    margin-bottom:30px;
    box-shadow:0 8px 25px rgba(0,0,0,.05);
}

.hero h2{
    color:var(--brown);
    font-weight:700;
}

.hero p{
    color:#8A7A6A;
}

.stats-card{

    background:#fff;
    border:1px solid var(--border);
    border-radius:18px;
    padding:20px;
    text-align:center;
}

.add-card{

    background:#fff;
    border:1px solid var(--border);
    border-radius:20px;
    box-shadow:0 8px 20px rgba(0,0,0,.04);
    overflow:hidden;
    margin-bottom:30px;
}

.add-header{

    background:#F8F4EC;
    color:var(--brown);
    padding:18px 25px;
    font-size:18px;
    font-weight:600;
    cursor:pointer;
}

.form-control{

    border-radius:12px;
    border:1px solid var(--border);
    height:50px;
}

.form-control:focus{

    border-color:#D8C5A6;
    box-shadow:none;
}

.btn-nafar{

    background:var(--brown);
    color:white;
    border-radius:50px;
    padding:12px 30px;
    border:none;
    transition:.3s;
}

.btn-nafar:hover{

    background:#54361d;
    color:white;
}

.section-title{

    color:var(--brown);
    font-weight:700;
    margin-bottom:20px;
}

.subject-card{

    background:#fff;
    border:1px solid var(--border);
    border-radius:20px;
    transition:.30s;
    overflow:hidden;
    box-shadow:0 5px 18px rgba(0,0,0,.04);
}

.subject-card:hover{

    transform:translateY(-6px);

    box-shadow:0 12px 30px rgba(0,0,0,.08);
}

.subject-body{

    padding:25px;
}

.icon-box{

    width:65px;
    height:65px;
    border-radius:18px;
    background:#F8F4EC;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:28px;
    color:var(--brown);
}

.subject-title{

    color:var(--brown);
    font-size:22px;
    font-weight:700;
}

.badge-soft{

    background:#F8F4EC;
    color:var(--brown);
    border:1px solid var(--border);
    padding:8px 14px;
    border-radius:30px;
    font-size:13px;
}



.subject-card:hover .arrow{

    background:var(--brown);
    color:white;
}

.empty{

    border:2px dashed var(--border);
    border-radius:20px;
    padding:60px;
    text-align:center;
    color:#777;
    background:white;
}
</style>

<div class="container py-5">

@if(session('error'))

<div class="alert alert-warning rounded-4 shadow-sm">

<i class="bi bi-exclamation-triangle-fill me-2"></i>

{{ session('error') }}

</div>

@endif

@if(!$user->is_subscribed)
    <div class="alert alert-warning d-flex justify-content-between align-items-center">

        <div>
            <strong>Passez à NafarBox Premium</strong><br>
            Débloquez toutes les fonctionnalités sans limitation.
        </div>

        <a href="{{ route('forfaits.index') }}" class="btn btn-success">
            <i class="bi bi-gem"></i> Voir les forfaits
        </a>

    </div>
@endif

<div class="hero">

<div class="row align-items-center">

<div class="col-lg-8">

<h2>

📚 Mes matières

</h2>

<p>

Organise tes connaissances par matière puis ajoute des chapitres et des fiches de révision.

</p>

</div>

<div class="col-lg-4">

<div class="stats-card">

<i class="bi bi-book-half fs-1 text-success"></i>

<h3 class="mt-3 mb-0">

{{ $matieres->count() }}

</h3>

<small class="text-muted">

Matières enregistrées


</small>
    <a href="{{ route('matieres.create') }}"
       class="btn btn-light shadow-sm px-4 py-3 rounded-pill"
       style="border:1px solid #F3EBDD;color:#654321;">

        <i class="bi bi-gear-fill me-2"></i>

        Gérer les matières

    </a>
</div>

</div>

</div>

</div>


<div class="add-card">

<div class="add-header"

data-bs-toggle="collapse"

data-bs-target="#nouvelleMatiere">

<i class="bi bi-plus-circle-fill me-2"></i>

Ajouter une nouvelle matière

</div>

<div class="collapse"

id="nouvelleMatiere">

<div class="p-4">

<form

method="POST"

action="{{ route('matieres.store') }}">

@csrf

<div class="mb-3">

<input

type="text"

class="form-control"

name="matiere"

placeholder="Ex : Français"

required>

</div>

<button

class="btn btn-nafar">

<i class="bi bi-check-circle-fill me-2"></i>

Enregistrer

</button>

</form>

</div>

</div>

</div>

<div class="mb-4">

    <div class="input-group">

        <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-search"></i>
        </span>

        <input type="text"
               id="searchMatiere"
               class="form-control border-start-0"
               placeholder="Rechercher une matière...">

    </div>

</div>
<h4 class="section-title">

<i class="bi bi-collection-fill me-2"></i>

Mes matières

</h4>

<div class="row g-4">

@forelse($matieres as $matiere)

<div class="col-lg-6 matiere-item">

<a

href="{{ route('chapitres.createForMatiere',$matiere->id) }}"

class="text-decoration-none">

<div class="subject-card">

<div class="subject-body">

<div class="d-flex justify-content-between">

<div class="d-flex">

<div class="icon-box">

<i class="bi bi-book"></i>

</div>

<div class="ms-3">

<h4 class="subject-title">

{{ $matiere->matiere }}

</h4>

<div class="mt-2">

<span class="badge-soft">

<i class="bi bi-journal-bookmark-fill"></i>

{{ $matiere->chapitres->count() }}

chapitres

</span>

<span class="badge-soft">

<i class="bi bi-journal-text"></i>

{{ $matiere->notes->count() }}

notes

</span>

</div>

</div>

</div>

<!-- <div class="arrow">

<i class="bi bi-chevron-right"></i>

</div> -->

</div>

</div>

</div>

</a>

</div>
@empty

<div class="col-12">

    <div class="empty">

        <i class="bi bi-book display-1 text-secondary"></i>

        <h3 class="mt-4 fw-bold" style="color:#654321;">

            Aucune matière

        </h3>

        <p class="text-muted">

            Commence par créer ta première matière pour organiser tes révisions.

        </p>

        <button
            class="btn btn-nafar mt-3"
            data-bs-toggle="collapse"
            data-bs-target="#nouvelleMatiere">

            <i class="bi bi-plus-circle-fill me-2"></i>

            Créer une matière

        </button>

    </div>

</div>

@endforelse

</div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded',function(){

    const input=document.getElementById('searchMatiere');

    if(input){

        input.addEventListener('keyup',function(){

            let valeur=this.value.toLowerCase();

            document.querySelectorAll('.subject-card').forEach(function(card){

                let texte=card.querySelector('.subject-title')
                    .innerText
                    .toLowerCase();

                card.parentElement.parentElement.style.display=
                    texte.includes(valeur)
                    ? 'block'
                    : 'none';

            });

        });

    }

});

</script>

@endpush

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('searchMatiere');

    input.addEventListener('keyup', function () {

        let recherche = this.value.toLowerCase().trim();

        document.querySelectorAll('.matiere-item').forEach(function(item){

            let nom = item.querySelector('.subject-title')
                          .textContent
                          .toLowerCase();

            if(nom.includes(recherche)){

                item.style.display='block';

            }else{

                item.style.display='none';

            }

        });

    });

});

</script>

@endpush