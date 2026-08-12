(function () {


    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    const DB_NAME =
        'nafarbox';


    /*
    | On utilise une version supérieure pour pouvoir
    | créer le store brouillons s'il n'existe pas.
    */

    const DB_VERSION =
        7;


    const STORE_NAME =
        'brouillons';



    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    let db =
        null;


    let timer =
        null;


    let brouillonActif =
        true;


    let initialisationFaite =
        false;


    /*
    |--------------------------------------------------------------------------
    | CHAPITRE
    |--------------------------------------------------------------------------
    */

    function getChapitreId() {

        const params =
            new URLSearchParams(
                window.location.search
            );


        return params.get('id');

    }



    /*
    |--------------------------------------------------------------------------
    | ID BROUILLON
    |--------------------------------------------------------------------------
    */

    function getBrouillonId() {

        const chapitreId =
            getChapitreId();


        return (
            'brouillon_note_' +
            chapitreId
        );

    }



    /*
    |--------------------------------------------------------------------------
    | OUVRIR INDEXEDDB
    |--------------------------------------------------------------------------
    */

    function ouvrirDB() {

        return new Promise(
            function (
                resolve,
                reject
            ) {


                const request =
                    indexedDB.open(
                        DB_NAME,
                        DB_VERSION
                    );


                request.onupgradeneeded =
                    function (event) {

                        const database =
                            event.target.result;


                        /*
                        |--------------------------------------------------------------------------
                        | Créer le store brouillons
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !database
                                .objectStoreNames
                                .contains(
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

            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | CHARGER LE BROUILLON
    |--------------------------------------------------------------------------
    */

    async function chargerBrouillon() {


        const chapitreId =
            getChapitreId();


        if (!chapitreId) {

            return;

        }


        if (!window.versoEditor) {

            console.log(
                '⏳ CKEditor pas encore prêt'
            );

            return;

        }


        try {


            if (!db) {

                db =
                    await ouvrirDB();

            }


            if (
                !db.objectStoreNames.contains(
                    STORE_NAME
                )
            ) {

                console.error(
                    '❌ Store brouillons introuvable'
                );

                return;

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
                            '📝 Aucun brouillon pour le chapitre',
                            chapitreId
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

                    window.versoEditor.setData(
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
    | SAUVEGARDER LE BROUILLON
    |--------------------------------------------------------------------------
    */

    async function sauvegarderBrouillon() {


        if (!brouillonActif) {

            return;

        }


        const chapitreId =
            getChapitreId();


        if (!chapitreId) {

            return;

        }


        if (!window.versoEditor) {

            return;

        }


        try {


            if (!db) {

                db =
                    await ouvrirDB();

            }


            const recto =
                document
                    .getElementById(
                        'recto'
                    )
                    ?.value || '';


            const verso =
                window.versoEditor.getData();


            console.log(
                '✏️ Contenu actuel :',
                {
                    recto: recto,
                    verso: verso
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Ne pas créer de brouillon vide
            |--------------------------------------------------------------------------
            */

            if (
                recto.trim() === '' &&
                verso.trim() === ''
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | OBJET BROUILLON
            |--------------------------------------------------------------------------
            */

            const brouillon = {

                id:
                    getBrouillonId(),

                chapitre_id:
                    Number(
                        chapitreId
                    ),

                recto:
                    recto,

                verso:
                    verso,

                updated_at:
                    new Date()
                        .toISOString()

            };


            /*
            |--------------------------------------------------------------------------
            | ENREGISTRER
            |--------------------------------------------------------------------------
            */

            const transaction =
                db.transaction(
                    STORE_NAME,
                    'readwrite'
                );


            const store =
                transaction.objectStore(
                    STORE_NAME
                );


            store.put(
                brouillon
            );


            transaction.oncomplete =
                function () {

                    console.log(
                        '💾 Brouillon sauvegardé :',
                        brouillon
                    );

                };


            transaction.onerror =
                function (event) {

                    console.error(
                        '❌ Erreur sauvegarde brouillon :',
                        event
                            .target
                            .error
                    );

                };


        } catch (error) {

            console.error(
                '❌ Erreur brouillon :',
                error
            );

        }

    }



    /*
    |--------------------------------------------------------------------------
    | SAUVEGARDE APRÈS MODIFICATION
    |--------------------------------------------------------------------------
    */

    function programmerSauvegarde() {


        if (!brouillonActif) {

            return;

        }


        clearTimeout(
            timer
        );


        timer =
            setTimeout(
                function () {

                    sauvegarderBrouillon();

                },
                1000
            );

    }



    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER LE BROUILLON
    |--------------------------------------------------------------------------
    */

    window.supprimerBrouillon =
        async function () {


            const chapitreId =
                getChapitreId();


            if (!chapitreId) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Arrêter les sauvegardes
            |--------------------------------------------------------------------------
            */

            brouillonActif =
                false;


            clearTimeout(
                timer
            );


            try {


                if (!db) {

                    db =
                        await ouvrirDB();

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


                store.delete(
                    id
                );


                await new Promise(
                    function (
                        resolve,
                        reject
                    ) {

                        transaction.oncomplete =
                            function () {

                                console.log(
                                    '🗑️ Brouillon supprimé :',
                                    id
                                );


                                resolve();

                            };


                        transaction.onerror =
                            function (event) {

                                reject(
                                    event
                                        .target
                                        .error
                                );

                            };

                    }
                );


            } catch (error) {

                console.error(
                    '❌ Erreur suppression brouillon :',
                    error
                );

            }

        };



    /*
    |--------------------------------------------------------------------------
    | INITIALISER LE SYSTÈME
    |--------------------------------------------------------------------------
    */

    window.initialiserBrouillon =
        async function () {


            if (initialisationFaite) {

                return;

            }


            initialisationFaite =
                true;


            console.log(
                '🚀 Initialisation système brouillon'
            );


            try {


                db =
                    await ouvrirDB();


                /*
                |--------------------------------------------------------------------------
                | Restaurer le brouillon
                |--------------------------------------------------------------------------
                */

                await chargerBrouillon();


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

                            programmerSauvegarde();

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | VERSO CKEDITOR
                |--------------------------------------------------------------------------
                */

                window.versoEditor
                    .model
                    .document
                    .on(
                        'change:data',
                        function () {

                            programmerSauvegarde();

                        }
                    );


                console.log(
                    '✅ Système brouillon prêt'
                );


            } catch (error) {

                console.error(
                    '❌ Impossible d’initialiser les brouillons :',
                    error
                );

            }

        };


})();