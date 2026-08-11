<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ID du chapitre
    |--------------------------------------------------------------------------
    */

    const chapitreId =
        document.getElementById('chapitre_id')?.value;


    if (!chapitreId) {

        console.error(
            '❌ ID chapitre introuvable'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    let db = null;

    let editorRecto = null;
    let editorVerso = null;


    /*
    |--------------------------------------------------------------------------
    | Ouvrir IndexedDB
    |--------------------------------------------------------------------------
    */

    const request =
        indexedDB.open(
            'nafarbox',
            5
        );


    /*
    |--------------------------------------------------------------------------
    | Création du store brouillons
    |--------------------------------------------------------------------------
    */

    request.onupgradeneeded =
        function (event) {

            const database =
                event.target.result;


            if (
                !database.objectStoreNames.contains(
                    'brouillons'
                )
            ) {

                database.createObjectStore(
                    'brouillons',
                    {
                        keyPath: 'chapitre_id'
                    }
                );


                console.log(
                    '✅ Store brouillons créé'
                );

            }

        };


    /*
    |--------------------------------------------------------------------------
    | Base ouverte
    |--------------------------------------------------------------------------
    */

    request.onsuccess =
        function (event) {

            db =
                event.target.result;


            console.log(
                '✅ IndexedDB brouillon ouverte'
            );


            initialiserEditeurs();

        };


    /*
    |--------------------------------------------------------------------------
    | Erreur
    |--------------------------------------------------------------------------
    */

    request.onerror =
        function (event) {

            console.error(
                '❌ IndexedDB :',
                event.target.error
            );

        };


    /*
    |--------------------------------------------------------------------------
    | Initialiser les éditeurs
    |--------------------------------------------------------------------------
    */

    function initialiserEditeurs() {

        /*
        | IMPORTANT :
        | Ici tu dois utiliser tes éditeurs CKEditor
        | déjà présents dans ta vue.
        |
        | Si tu les initialises ailleurs,
        | appelle simplement chargerBrouillon()
        | après leur création.
        */

        if (
            typeof window.editorRecto ===
            'undefined' ||
            typeof window.editorVerso ===
            'undefined'
        ) {

            console.warn(
                '⚠️ CKEditor pas encore disponible'
            );

            return;

        }


        editorRecto =
            window.editorRecto;

        editorVerso =
            window.editorVerso;


        chargerBrouillon();


        /*
        | Sauvegarde toutes les 3 secondes
        */

        setInterval(
            sauvegarderBrouillon,
            3000
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Charger le brouillon
    |--------------------------------------------------------------------------
    */

    function chargerBrouillon() {

        const transaction =
            db.transaction(
                'brouillons',
                'readonly'
            );


        const store =
            transaction.objectStore(
                'brouillons'
            );


        const request =
            store.get(
                Number(chapitreId)
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


                editorRecto.setData(
                    brouillon.recto || ''
                );


                editorVerso.setData(
                    brouillon.verso || ''
                );

            };

    }


    /*
    |--------------------------------------------------------------------------
    | Sauvegarder
    |--------------------------------------------------------------------------
    */

    function sauvegarderBrouillon() {

        if (!db) {
            return;
        }


        if (
            !editorRecto ||
            !editorVerso
        ) {
            return;
        }


        const recto =
            editorRecto.getData();


        const verso =
            editorVerso.getData();


        /*
        | Ne pas sauvegarder si tout est vide
        */

        if (
            recto.trim() === '' &&
            verso.trim() === ''
        ) {

            return;
        }


        const brouillon = {

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
                'brouillons',
                'readwrite'
            );


        transaction
            .objectStore('brouillons')
            .put(brouillon);


        console.log(
            '💾 Brouillon sauvegardé'
        );

    }

});

function demarrerBrouillon() {

    if (!window.db) {
        console.log("⏳ IndexedDB pas encore prête");
        return;
    }

    if (!window.editorRecto || !window.editorVerso) {
        console.log("⏳ CKEditor pas encore prêt");
        return;
    }

    console.log("✅ Brouillon prêt");

    chargerBrouillon();

    setInterval(
        sauvegarderBrouillon,
        3000
    );
}
</script>