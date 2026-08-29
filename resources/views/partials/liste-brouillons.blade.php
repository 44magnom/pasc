<div id="listeBrouillonsContainer"
     style="display:none;">
    {{-- Le contenu sera généré par JavaScript --}}
</div>


<script>

(function () {


    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    const DB_NAME = 'nafarbox';

    const DB_VERSION = 8;

    const STORE_NAME = 'brouillons';



    /*
    |--------------------------------------------------------------------------
    | OUVRIR INDEXEDDB
    |--------------------------------------------------------------------------
    */

    function ouvrirDB() {

        return new Promise((resolve, reject) => {

            const request = indexedDB.open(
                DB_NAME,
                DB_VERSION
            );


            request.onsuccess = function (event) {

                resolve(
                    event.target.result
                );

            };


            request.onerror = function (event) {

                reject(
                    event.target.error
                );

            };

        });

    }



    /*
    |--------------------------------------------------------------------------
    | RÉCUPÉRER TOUS LES BROUILLONS
    |--------------------------------------------------------------------------
    */

    async function recupererBrouillons() {

        try {

            const db =
                await ouvrirDB();


            /*
            |--------------------------------------------------------------------------
            | Vérifier que le store existe
            |--------------------------------------------------------------------------
            */

            if (
                !db.objectStoreNames.contains(
                    STORE_NAME
                )
            ) {

                console.log(
                    'ℹ️ Aucun store brouillons'
                );

                return [];

            }


            const transaction =
                db.transaction(
                    STORE_NAME,
                    'readonly'
                );


            const store =
                transaction.objectStore(
                    STORE_NAME
                );


            const request =
                store.getAll();


            return new Promise((resolve, reject) => {


                request.onsuccess =
                    function () {

                        resolve(
                            request.result || []
                        );

                    };


                request.onerror =
                    function (event) {

                        reject(
                            event.target.error
                        );

                    };

            });


        } catch (error) {

            console.error(
                '❌ Erreur récupération brouillons :',
                error
            );

            return [];

        }

    }



    /*
    |--------------------------------------------------------------------------
    | ÉCHAPPER LE HTML
    |--------------------------------------------------------------------------
    */

    function echapperHTML(texte) {

        const div =
            document.createElement('div');


        div.textContent =
            texte || '';


        return div.innerHTML;

    }



    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER LES BALISES HTML
    |--------------------------------------------------------------------------
    |
    | Le verso contient du HTML venant de CKEditor.
    | On ne veut pas afficher tout le HTML dans l'aperçu.
    |
    */

    function texteSimple(html) {

        const div =
            document.createElement('div');


        div.innerHTML =
            html || '';


        return (
            div.textContent ||
            div.innerText ||
            ''
        ).trim();

    }



    /*
    |--------------------------------------------------------------------------
    | FORMATER LA DATE
    |--------------------------------------------------------------------------
    */

    function formaterDate(date) {

        if (!date) {

            return '';

        }


        const d =
            new Date(date);


        if (isNaN(d.getTime())) {

            return '';

        }


        return d.toLocaleDateString(
            'fr-FR',
            {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | AFFICHER LES BROUILLONS
    |--------------------------------------------------------------------------
    */

    async function afficherBrouillons() {

        const container =
            document.getElementById(
                'listeBrouillonsContainer'
            );


        if (!container) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Par défaut : cacher le bloc
        |--------------------------------------------------------------------------
        */

        container.style.display = 'none';

        container.innerHTML = '';


        /*
        |--------------------------------------------------------------------------
        | Récupérer les brouillons
        |--------------------------------------------------------------------------
        */

        const brouillons =
            await recupererBrouillons();


        /*
        |--------------------------------------------------------------------------
        | Aucun brouillon
        |--------------------------------------------------------------------------
        */

        if (
            !brouillons ||
            brouillons.length === 0
        ) {

            console.log(
                '📝 Aucun brouillon à afficher'
            );

            /*
            | Le conteneur reste caché.
            */

            container.style.display = 'none';

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Il y a au moins un brouillon
        |--------------------------------------------------------------------------
        |
        | Maintenant seulement, on affiche le bloc.
        |
        */

        container.style.display = 'block';


        /*
        |--------------------------------------------------------------------------
        | Trier du plus récent au plus ancien
        |--------------------------------------------------------------------------
        */

        brouillons.sort(
            function (a, b) {

                return new Date(
                    b.updated_at || 0
                ) -
                new Date(
                    a.updated_at || 0
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Générer les cartes
        |--------------------------------------------------------------------------
        */

        let html = '';


        html += `

            <div class="mb-4">

                <h5
                    class="fw-semibold mb-3"
                    style="color:#654321;"
                >
                    Brouillons
                </h5>


                <div class="row g-3">

        `;


        brouillons.forEach(
            function (brouillon) {


                /*
                |--------------------------------------------------------------------------
                | RECTO
                |--------------------------------------------------------------------------
                */

                const recto =
                    echapperHTML(
                        brouillon.recto ||
                        'Sans titre'
                    );


                /*
                |--------------------------------------------------------------------------
                | VERSO
                |--------------------------------------------------------------------------
                */

                const verso =
                    echapperHTML(
                        texteSimple(
                            brouillon.verso
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | DATE
                |--------------------------------------------------------------------------
                */

                const date =
                    formaterDate(
                        brouillon.updated_at
                    );


                /*
                |--------------------------------------------------------------------------
                | CARTE
                |--------------------------------------------------------------------------
                */

                html += `

                    <div class="col-12 col-md-6 col-lg-4">

                        <div
                            class="card h-100 shadow-sm"
                            style="
                                background-color:#F8F3EB;
                                border:1px solid #D2B48C;
                            "
                        >

                            <div class="card-body">

                                <div
                                    class="small mb-2"
                                    style="
                                        color:#8a8178;
                                    "
                                >
                                    Brouillon
                                </div>


                                <h6
                                    class="fw-semibold mb-2"
                                    style="
                                        color:#654321;
                                    "
                                >
                                    ${recto}
                                </h6>


                                ${
                                    verso
                                    ?
                                    `
                                    <p
                                        class="mb-3"
                                        style="
                                            color:#5C554D;
                                            font-size:14px;
                                        "
                                    >
                                        ${verso.substring(0, 120)}
                                        ${
                                            verso.length > 120
                                            ? '...'
                                            : ''
                                        }
                                    </p>
                                    `
                                    :
                                    ''
                                }


                                ${
                                    date
                                    ?
                                    `
                                    <div
                                        class="small mb-3"
                                        style="
                                            color:#8a8178;
                                        "
                                    >
                                        Modifié le ${date}
                                    </div>
                                    `
                                    :
                                    ''
                                }


                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary ouvrir-brouillon"
                                    data-chapitre-id="${brouillon.chapitre_id}"
                                >
                                    Continuer
                                </button>


                            </div>

                        </div>

                    </div>

                `;

            }
        );


        html += `

                </div>

            </div>

        `;


        /*
        |--------------------------------------------------------------------------
        | Injecter le HTML
        |--------------------------------------------------------------------------
        */

        container.innerHTML =
            html;


        /*
        |--------------------------------------------------------------------------
        | Afficher le conteneur
        |--------------------------------------------------------------------------
        */

        container.style.display =
            'block';


        /*
        |--------------------------------------------------------------------------
        | Boutons "Continuer"
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.ouvrir-brouillon'
            )
            .forEach(
                function (button) {


                    button.addEventListener(
                        'click',
                        function () {


                            const chapitreId =
                                this.dataset.chapitreId;


                            /*
                            |--------------------------------------------------------------------------
                            | Route de création
                            |--------------------------------------------------------------------------
                            */

                            window.location.href =
                                `/chapitres/${chapitreId}/notes/create`;

                        }
                    );

                }
            );

    }



    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            afficherBrouillons();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | RENDRE LA FONCTION DISPONIBLE
    |--------------------------------------------------------------------------
    */

    window.actualiserListeBrouillons =
        afficherBrouillons;


})();

</script>