(function () {

    const DB_NAME = 'nafarbox';

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    | Ta base principale est déjà en version 7.
    */

    const DB_VERSION = 7;

    const STORE_NAME = 'brouillons';

    let db = null;

    let intervalSauvegarde = null;


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

                const database =
                    event.target.result;


                /*
                | Vérifier que le store existe
                */

                if (
                    !database.objectStoreNames.contains(
                        STORE_NAME
                    )
                ) {

                    console.error(
                        '❌ Store brouillons introuvable'
                    );

                    reject(
                        new Error(
                            'Store brouillons introuvable'
                        )
                    );

                    return;
                }


                db = database;


                console.log(
                    '✅ IndexedDB brouillons ouverte'
                );


                resolve(database);

            };


            request.onerror = function (event) {

                console.error(
                    '❌ Erreur ouverture IndexedDB :',
                    event.target.error
                );


                reject(
                    event.target.error
                );

            };

        });

    }



    /*
    |--------------------------------------------------------------------------
    | RÉCUPÉRER LE CHAPITRE
    |--------------------------------------------------------------------------
    */

    function getChapitreId() {

        const select =
            document.getElementById(
                'chapitre'
            );


        if (
            select &&
            select.value
        ) {

            return Number(
                select.value
            );

        }


        return null;

    }



    /*
    |--------------------------------------------------------------------------
    | RÉCUPÉRER LE RECTO
    |--------------------------------------------------------------------------
    */

    function getRecto() {

        const recto =
            document.getElementById(
                'recto'
            );


        if (!recto) {

            return '';

        }


        return recto.value || '';

    }



    /*
    |--------------------------------------------------------------------------
    | RÉCUPÉRER LE VERSO
    |--------------------------------------------------------------------------
    */

    function getVerso() {

        /*
        | CKEditor
        */

        if (
            window.versoEditor
        ) {

            return window.versoEditor.getData();

        }


        /*
        | Fallback textarea
        */

        const verso =
            document.getElementById(
                'verso'
            );


        if (!verso) {

            return '';

        }


        return verso.value || '';

    }



    /*
    |--------------------------------------------------------------------------
    | CHARGER BROUILLON
    |--------------------------------------------------------------------------
    */

    async function chargerBrouillon() {

        if (!db) {

            return;

        }


        const chapitreId =
            getChapitreId();


        if (!chapitreId) {

            console.log(
                '⏳ Aucun chapitre sélectionné'
            );

            return;

        }


        try {

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
                    chapitreId
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
                    | Recto
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
                    | Verso CKEditor
                    */

                    if (
                        window.versoEditor
                    ) {

                        window.versoEditor.setData(
                            brouillon.verso || ''
                        );

                    }
                    else {

                        const verso =
                            document.getElementById(
                                'verso'
                            );


                        if (verso) {

                            verso.value =
                                brouillon.verso || '';

                        }

                    }


                    console.log(
                        '✅ Brouillon restauré'
                    );

                };


        }
        catch (error) {

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

        const chapitreId =
            getChapitreId();


        if (!chapitreId) {

            return;

        }


        if (!db) {

            return;

        }


        const recto =
            getRecto();


        const verso =
            getVerso();


        console.log(
            '✏️ Contenu actuel :',
            {
                recto: recto,
                verso: verso
            }
        );


        /*
        | Ne rien sauvegarder si tout est vide
        */

        if (
            recto.trim() === '' &&
            verso.trim() === ''
        ) {

            return;

        }


        const brouillon = {

            id:
                'brouillon_note_' +
                chapitreId,

            chapitre_id:
                chapitreId,

            recto:
                recto,

            verso:
                verso,

            updated_at:
                new Date().toISOString()

        };


        try {

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


            console.log(
                '💾 Brouillon sauvegardé'
            );

        }
        catch (error) {

            console.error(
                '❌ Erreur sauvegarde brouillon :',
                error
            );

        }

    }



    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER BROUILLON
    |--------------------------------------------------------------------------
    */

    window.supprimerBrouillon =
        async function () {


            const chapitreId =
                getChapitreId();


            if (!chapitreId) {

                console.log(
                    '⚠️ Aucun chapitre pour supprimer le brouillon'
                );

                return;

            }


            if (!db) {

                console.log(
                    '⚠️ IndexedDB pas encore prête'
                );

                return;

            }


            try {

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
                    'brouillon_note_' +
                    chapitreId;


                const request =
                    store.delete(id);


                request.onsuccess =
                    function () {

                        console.log(
                            '🗑️ Brouillon supprimé :',
                            id
                        );

                    };


                request.onerror =
                    function (event) {

                        console.error(
                            '❌ Erreur suppression brouillon :',
                            event.target.error
                        );

                    };

            }
            catch (error) {

                console.error(
                    '❌ Erreur suppression brouillon :',
                    error
                );

            }

        };



    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

    window.initialiserBrouillon =
        async function () {


            try {

                await ouvrirDB();


                console.log(
                    '✅ Système brouillon prêt'
                );


                /*
                | Si un chapitre est déjà sélectionné
                */

                if (
                    getChapitreId()
                ) {

                    await chargerBrouillon();

                }


                /*
                | Sauvegarde automatique
                | toutes les 3 secondes
                */

                if (
                    intervalSauvegarde
                ) {

                    clearInterval(
                        intervalSauvegarde
                    );

                }


                intervalSauvegarde =
                    setInterval(
                        sauvegarderBrouillon,
                        3000
                    );


            }
            catch (error) {

                console.error(
                    '❌ Impossible d’initialiser le système brouillon :',
                    error
                );

            }

        };



    /*
    |--------------------------------------------------------------------------
    | QUAND L'UTILISATEUR CHANGE DE CHAPITRE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'change',
        function (event) {


            if (
                event.target &&
                event.target.id === 'chapitre'
            ) {

                console.log(
                    '📚 Chapitre sélectionné :',
                    event.target.value
                );


                /*
                | Petit délai pour laisser
                | la sélection se stabiliser
                */

                setTimeout(
                    chargerBrouillon,
                    100
                );

            }

        }
    );



})();