
<div id="audioControls" class="d-none mt-3">

    <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">

<button class="btn btn-beige" id="btnLecture">
    ▶️
</button>

<button class="btn btn-beige" id="btnPause">
    ⏸
</button>

<button class="btn btn-beige" id="btnStop">
    ⏹
</button>
        <select id="repeat" class="form-select w-auto">
            <option value="1">1 fois</option>
            <option value="3" selected>3 fois</option>
            <option value="5">5 fois</option>
            <option value="10">10 fois</option>
            <option value="infini">∞</option>
        </select>

    </div>

</div>
<div class="d-flex justify-content-center mt-3">

    <button id="toggleAudio" class="audio-toggle">
        🔊
    </button>

</div>

@push('scripts')
<script>



/* ==========================
   LECTURE AUDIO
========================== */

let stopLecture = false;
let repetition = 0;
const btnPause = document.getElementById('btnPause');



// Convertit le HTML CKEditor en texte lisible
function htmlVersTexte(html) {

    html = html.replace(/<\/p>/gi, ". ");
    html = html.replace(/<br\s*\/?>/gi, ". ");
    html = html.replace(/<\/div>/gi, ". ");
    html = html.replace(/<\/li>/gi, ". ");
    html = html.replace(/<\/h[1-6]>/gi, ". ");

    const div = document.createElement("div");
    div.innerHTML = html;

    return div.textContent.replace(/\s+/g, " ").trim();
}

// Lance la lecture
function lancerLecture() {

    stopLecture = false;
    repetition = 0;

    speechSynthesis.cancel();

    lireCarte();
}

// Lit la carte
function lireCarte() {

    if (stopLecture) return;

    let texte = htmlVersTexte(notes[index].recto);

    // Si la réponse est visible, on la lit aussi
    if (reponseVisible) {

        texte += ". Réponse. " +
                 htmlVersTexte(notes[index].verso);

    }

    const speech = new SpeechSynthesisUtterance(texte);

    speech.lang = "fr-FR";

    // vitesse
    speech.rate = 1;

    speech.pitch = 1;
    speech.volume = 1;

    speech.onend = function () {

        if (stopLecture) return;

        repetition++;

        const max = document.getElementById("repeat").value;

        if (max === "infini") {

            lireCarte();

        } else if (repetition < parseInt(max)) {

            lireCarte();

        }

    };

    speechSynthesis.speak(speech);

}

// Arrêter
function arreterLecture() {

    stopLecture = true;

    speechSynthesis.cancel();

}

/* ==========================
   BOUTONS
========================== */

document.getElementById("btnLecture")
.addEventListener("click", lancerLecture);

document.getElementById("btnStop")
.addEventListener("click", arreterLecture);


btnPause.addEventListener('click', function () {

    if (speechSynthesis.speaking && !speechSynthesis.paused) {
        speechSynthesis.pause();
        this.innerHTML = "▶️ Reprendre";
    } else if (speechSynthesis.paused) {
        speechSynthesis.resume();
        this.innerHTML = "⏸ Pause";
    }

});
</script>
@endpush

@push('scripts')
<script>
const toggleAudio = document.getElementById('toggleAudio');
const audioControls = document.getElementById('audioControls');

toggleAudio.addEventListener('click', function () {

    audioControls.classList.toggle('d-none');

    if (audioControls.classList.contains('d-none')) {
        this.innerHTML = '🔊';
    } else {
        this.innerHTML = '✖';
    }

});


</script>

<style>
.audio-toggle{
    width:38px;
    height:38px;
    border-radius:50%;
    border:1px solid #c8b79c;
    background:#f4ede2;
    color:#8b6b3f;
    font-size:18px;
    cursor:pointer;

    display:flex;
    align-items:center;
    justify-content:center;

    transition:.2s ease;
}

.audio-toggle:hover{
    background:#e8dcc8;
    border-color:#b69a72;
    color:#6f542f;
}


</style>


<style>

.btn-beige{
    background-color: #b0aba2;   /* Beige foncé */
    border-color: #b08d57;
    color: #fff;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all .2s ease;
}

.btn-beige:hover{
    background-color: #9a7845;
    border-color: #9a7845;
    color: #fff;
}

.btn-beige:focus,
.btn-beige:active{
    background-color: #8a6a3d !important;
    border-color: #8a6a3d !important;
    color: #fff !important;
    box-shadow: 0 0 0 .2rem rgba(176,141,87,.25);
}
</style>
@endpush