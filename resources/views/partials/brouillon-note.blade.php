<script>

(function () {

    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    const DB_NAME = 'nafarbox';
    const DB_VERSION = 7;
    const STORE_NAME = 'brouillons';


    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    let db = null;
    let timerBrouillon = null;
    let brouillonActif = true;

    const chapitreId =
        document.getElementById('chapitre_id')?.value;


    /*
    |--------------------------------------------------------------------------
    | IMPORTANT :
    | Laravel vient-il de créer une note ?
    |--------------------------------------------------------------------------
    */

    const noteVientDEtreCreee =
        {{ session('note_created') ? 'true' : 'false' }};


    /*
    |--------------------------------------------------------------------------
    | OUVRIR INDEXEDDB
    |--------------------------------------------------------------------------
    */

    function ouvrirDBBrouillon() {

        return new Promise(function (resolve, reject) {

            const request = indexedDB.open(
                DB_NAME,
                DB_VERSION
            );


            request.onupgradeneeded =
                function (event) {

                    const database =
                        event.target.result;


                    if (
                        !database.objectStoreNames.contains(
                            STORE_NAME
                        )
                    ) {

                        database.createObjectStore(
                            STORE_NAME,
                            {
                                keyPath: 'id'
                            }
                        );

                        console.log(
                            '✅ Store brouillons créé'
                        );

                    }

                };


            request.onsuccess =
                function (event) {

                    resolve(
                        event.target.result
                    );

                };


            request.onerror =
                function (event) {

                    reject(
                        event.target.error
                    );

                };

        });

    }


    /*
    |--------------------------------------------------------------------------
    | ID DU BROUILLON
    |--------------------------------------------------------------------------
    */

    function getBrouillonId() {

        return 'brouillon_note_' + chapitreId;

    }


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER LE BROUILLON
    |--------------------------------------------------------------------------
    */

    window.supprimerBrouillon = async function () {

        if (!chapitreId) {

            console.error(
                '❌ chapitre_id absent'
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ARRÊTER TOUTE SAUVEGARDE
        |--------------------------------------------------------------------------
        */

        brouillonActif = false;

        clearTimeout(
            timerBrouillon
        );


        try {

            if (!db) {

                db =
                    await ouvrirDBBrouillon();

            }


            const transaction =
                db.transaction(
                    STORE_NAME,
                    'readwrite'
                );


            const store =
                transaction.objectStore(
                    STORE_NAME
                );


            const id =
                getBrouillonId();


            store.delete(id);


            transaction.oncomplete =
                function () {

                    console.log(
                        '🗑️ Brouillon supprimé :',
                        id
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | VIDER VISUELLEMENT LE FORMULAIRE
                    |--------------------------------------------------------------------------
                    */

                    const recto =
                        document.getElementById(
                            'recto'
                        );


                    if (recto) {

                        recto.value = '';

                    }


                    if (window.editorVerso) {

                        window.editorVerso.setData('');

                    }

                };


            transaction.onerror =
                function (event) {

                    console.error(
                        '❌ Erreur suppression brouillon :',
                        event.target.error
                    );

                };


        } catch (error) {

            console.error(
                '❌ Erreur suppression brouillon :',
                error
            );

        }

    };


    /*
    |--------------------------------------------------------------------------
    | CHARGER LE BROUILLON
    |--------------------------------------------------------------------------
    */

    async function chargerBrouillon() {

        /*
        |--------------------------------------------------------------------------
        | TRÈS IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Si Laravel vient de créer une note,
        | on NE DOIT PAS charger l'ancien brouillon.
        |
        */

        if (noteVientDEtreCreee) {

            console.log(
                '🚫 Note créée : brouillon non chargé'
            );

            return;

        }


        if (!chapitreId) {

            return;

        }


        if (!window.editorVerso) {

            setTimeout(
                chargerBrouillon,
                300
            );

            return;

        }


        try {

            if (!db) {

                db =
                    await ouvrirDBBrouillon();

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
                store.get(
                    getBrouillonId()
                );


            request.onsuccess =
                function () {

                    const brouillon =
                        request.result;


                    if (!brouillon) {

                        console.log(
                            '📝 Aucun brouillon'
                        );

                        return;

                    }


                    console.log(
                        '📝 Brouillon retrouvé :',
                        brouillon
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | RESTAURER RECTO
                    |--------------------------------------------------------------------------
                    */

                    const recto =
                        document.getElementById(
                            'recto'
                        );


                    if (recto) {

                        recto.value =
                            brouillon.recto || '';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | RESTAURER VERSO
                    |--------------------------------------------------------------------------
                    */

                    window.editorVerso.setData(
                        brouillon.verso || ''
                    );


                    console.log(
                        '✅ Brouillon restauré'
                    );

                };


        } catch (error) {

            console.error(
                '❌ Erreur chargement brouillon :',
                error
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SAUVEGARDER BROUILLON
    |--------------------------------------------------------------------------
    */

    async function sauvegarderBrouillon() {

        if (!brouillonActif) {

            return;

        }


        /*
        | Ne jamais sauvegarder immédiatement
        | après la création d'une note.
        */

        if (noteVientDEtreCreee) {

            return;

        }


        if (!chapitreId) {

            return;

        }


        if (!window.editorVerso) {

            return;

        }


        try {

            if (!db) {

                db =
                    await ouvrirDBBrouillon();

            }


            const recto =
                document
                    .getElementById('recto')
                    ?.value || '';


            const verso =
                window.editorVerso.getData();


            if (
                recto.trim() === '' &&
                verso.trim() === ''
            ) {

                return;

            }


            console.log(
                '✏️ Contenu actuel :',
                {
                    recto: recto,
                    verso: verso
                }
            );


            const brouillon = {

                id:
                    getBrouillonId(),

                chapitre_id:
                    Number(chapitreId),

                recto:
                    recto,

                verso:
                    verso,

                updated_at:
                    new Date().toISOString()

            };


            const transaction =
                db.transaction(
                    STORE_NAME,
                    'readwrite'
                );


            transaction
                .objectStore(STORE_NAME)
                .put(brouillon);


            transaction.oncomplete =
                function () {

                    console.log(
                        '💾 Brouillon sauvegardé'
                    );

                };


        } catch (error) {

            console.error(
                '❌ Erreur sauvegarde brouillon :',
                error
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SAUVEGARDE AVEC DÉLAI
    |--------------------------------------------------------------------------
    */

    function sauvegarderAvecDelai() {

        if (!brouillonActif) {

            return;

        }


        if (noteVientDEtreCreee) {

            return;

        }


        clearTimeout(
            timerBrouillon
        );


        timerBrouillon =
            setTimeout(
                function () {

                    sauvegarderBrouillon();

                },
                700
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


            /*
            |--------------------------------------------------------------------------
            | CAS 1 : NOTE CRÉÉE
            |--------------------------------------------------------------------------
            */

            if (noteVientDEtreCreee) {

                console.log(
                    '✅ Note créée avec succès'
                );


                /*
                | Supprimer immédiatement le brouillon.
                */

                window.supprimerBrouillon();


                /*
                | Ne surtout pas initialiser les
                | événements de sauvegarde.
                */

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | CAS 2 : PAGE NORMALE
            |--------------------------------------------------------------------------
            */

            function initialiser() {

                if (!window.editorVerso) {

                    setTimeout(
                        initialiser,
                        300
                    );

                    return;

                }


                console.log(
                    '✅ Système brouillon prêt'
                );


                /*
                |--------------------------------------------------------------------------
                | Charger le brouillon
                |--------------------------------------------------------------------------
                */

                chargerBrouillon();


                /*
                |--------------------------------------------------------------------------
                | RECTO
                |--------------------------------------------------------------------------
                */

                const recto =
                    document.getElementById(
                        'recto'
                    );


                if (recto) {

                    recto.addEventListener(
                        'input',
                        function () {

                            sauvegarderAvecDelai();

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | VERSO
                |--------------------------------------------------------------------------
                */

                window.editorVerso
                    .model
                    .document
                    .on(
                        'change:data',
                        function () {

                            sauvegarderAvecDelai();

                        }
                    );

            }


            initialiser();

        }
    );


})();

</script>