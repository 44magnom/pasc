<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    📖 Détails de la note
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <h6 class="fw-bold mb-3">
                    Recto
                </h6>

                <div id="modalRecto" class="ck-content"></div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3">
                    Verso
                </h6>

                <div id="modalVerso" class="ck-content"></div>

            </div>

        </div>

    </div>

</div>

@push('styles')

<style>

/******************************
 * Contenu CKEditor
 ******************************/

.ck-content{

    font-size:1rem;
    line-height:1.8;
    word-break:break-word;

}

.ck-content p{

    margin-bottom:.8rem;

}

.ck-content ul,
.ck-content ol{

    padding-left:30px;
    margin-bottom:1rem;

}

.ck-content h1{

    font-size:2rem;
    margin-bottom:1rem;

}

.ck-content h2{

    font-size:1.6rem;
    margin-bottom:.8rem;

}

.ck-content h3{

    font-size:1.3rem;
    margin-bottom:.8rem;

}

.ck-content blockquote{

    border-left:4px solid #0d6efd;
    padding-left:15px;
    color:#6c757d;
    margin:20px 0;
    font-style:italic;

}

.ck-content pre{

    background:#f8f9fa;
    padding:15px;
    border-radius:8px;
    overflow:auto;

}

.ck-content table{

    width:100%;
    border-collapse:collapse;
    margin:20px 0;

}

.ck-content table,
.ck-content td,
.ck-content th{

    border:1px solid #dee2e6;

}

.ck-content td,
.ck-content th{

    padding:10px;

}

.ck-content img{

    max-width:100%;
    height:auto;
    display:block;
    margin:15px auto;
    border-radius:8px;

}

.ck-content figure{

    text-align:center;

}

.ck-content figcaption{

    color:#6c757d;
    font-size:.9rem;
    margin-top:8px;

}

</style>

@endpush

@push('scripts')

<script>

const noteModal = document.getElementById('noteModal');

noteModal.addEventListener('show.bs.modal', function (event) {

    const bouton = event.relatedTarget;

    document.getElementById('modalRecto').innerHTML =
        bouton.dataset.recto;

    document.getElementById('modalVerso').innerHTML =
        bouton.dataset.verso;

});

</script>

@endpush