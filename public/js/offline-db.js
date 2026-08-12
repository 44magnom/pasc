"use strict";


let db;

const DB_NAME = "nafarbox";
const DB_VERSION = 7;

const request = indexedDB.open(DB_NAME, DB_VERSION);


/*
|--------------------------------------------------------------------------
| OUVERTURE / MISE À JOUR DE LA BASE
|--------------------------------------------------------------------------
*/

request.onupgradeneeded = function (event) {

    db = event.target.result;


    /*
    |--------------------------------------------------------------------------
    | MATIERES
    |--------------------------------------------------------------------------
    */

    if (!db.objectStoreNames.contains("matieres")) {

        const store = db.createObjectStore("matieres", {
            keyPath: "id"
        });

        store.createIndex(
            "user_id",
            "user_id",
            { unique: false }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CHAPITRES
    |--------------------------------------------------------------------------
    */

    if (!db.objectStoreNames.contains("chapitres")) {

        const store = db.createObjectStore("chapitres", {
            keyPath: "id"
        });

        store.createIndex(
            "matiere_id",
            "matiere_id",
            { unique: false }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NOTES
    |--------------------------------------------------------------------------
    */
/*
|--------------------------------------------------------------------------
| NOTES
|--------------------------------------------------------------------------
*/

let notesStore;

if (!db.objectStoreNames.contains("notes")) {

    notesStore = db.createObjectStore("notes", {
        keyPath: "local_id"
    });

} else {

    notesStore = event.target.transaction.objectStore("notes");

}

if (!db.objectStoreNames.contains('brouillons')) {

    db.createObjectStore(
        'brouillons',
        {
            keyPath: 'id'
        }
    );

    console.log(
        '✅ Store brouillons créé'
    );

}
/*
| Index ID
*/

if (!notesStore.indexNames.contains("id")) {

    notesStore.createIndex(
        "id",
        "id",
        { unique: false }
    );

}


/*
| Index chapitre_id
*/

if (!notesStore.indexNames.contains("chapitre_id")) {

    notesStore.createIndex(
        "chapitre_id",
        "chapitre_id",
        { unique: false }
    );

}


/*
| Index synchronisation
*/

if (!notesStore.indexNames.contains("is_synced")) {

    notesStore.createIndex(
        "is_synced",
        "is_synced",
        { unique: false }
    );

}


    /*
    |--------------------------------------------------------------------------
    | FILE D'ATTENTE
    |--------------------------------------------------------------------------
    */

    if (!db.objectStoreNames.contains("sync_queue")) {

        const store = db.createObjectStore("sync_queue", {
            keyPath: "id",
            autoIncrement: true
        });

        store.createIndex(
            "table",
            "table",
            { unique: false }
        );

        store.createIndex(
            "action",
            "action",
            { unique: false }
        );

        store.createIndex(
            "local_id",
            "local_id",
            { unique: false }
        );

    }

};


/*
|--------------------------------------------------------------------------
| BASE LOCALE PRÊTE
|--------------------------------------------------------------------------
*/

request.onsuccess = function (event) {

    db = event.target.result;

    console.log("✅ Base locale prête.");


    if (navigator.onLine) {

        /*
        |--------------------------------------------------------------------------
        | 1. Envoyer les notes créées hors connexion
        |--------------------------------------------------------------------------
        */

        synchroniserNotes();
        // Serveur → IndexedDB
    synchroniserStructureDepuisServeur();



        /*
        |--------------------------------------------------------------------------
        | 2. Télécharger les notes du serveur
        |--------------------------------------------------------------------------
        */

        synchroniserDepuisServeur();

    }

};


request.onerror = function (event) {

    console.error(
        "❌ Erreur IndexedDB :",
        event.target.error
    );

};


/*
|--------------------------------------------------------------------------
| ENREGISTRER UNE NOTE HORS CONNEXION
|--------------------------------------------------------------------------
*/

function saveOfflineNote(note)
{

    console.log("💾 Enregistrement local :", note);


    const transaction =
        db.transaction("notes", "readwrite");


    const store =
        transaction.objectStore("notes");


    const request =
        store.add(note);


    request.onsuccess = function () {

        console.log(
            "✅ Note enregistrée localement :",
            note.local_id
        );

    };


    request.onerror = function (e) {

        console.error(
            "❌ Erreur d'enregistrement local"
        );

        console.error(e);

    };

}


/*
|--------------------------------------------------------------------------
| LIRE TOUTES LES NOTES
|--------------------------------------------------------------------------
*/

function getOfflineNotes(callback)
{

    const transaction =
        db.transaction("notes", "readonly");


    const store =
        transaction.objectStore("notes");


    const request =
        store.getAll();


    request.onsuccess = function () {

        callback(request.result);

    };


    request.onerror = function (event) {

        console.error(
            "❌ Erreur lecture notes :",
            event.target.error
        );

        callback([]);

    };

}


/*
|--------------------------------------------------------------------------
| SUPPRIMER UNE NOTE LOCALE
|--------------------------------------------------------------------------
*/

function deleteOfflineNote(local_id)
{

    const transaction =
        db.transaction("notes", "readwrite");


    const store =
        transaction.objectStore("notes");


    const request =
        store.delete(local_id);


    request.onsuccess = function () {

        console.log(
            "🗑️ Note locale supprimée :",
            local_id
        );

    };


    request.onerror = function (event) {

        console.error(
            "❌ Erreur suppression note :",
            event.target.error
        );

    };

}


/*
|--------------------------------------------------------------------------
| LOCAL → SERVEUR
|
| Envoie uniquement les notes qui ne sont pas encore synchronisées.
|--------------------------------------------------------------------------
*/

async function synchroniserNotes()
{

    if (!navigator.onLine) {

        console.log(
            "📴 Pas de connexion. Synchronisation impossible."
        );

        return;

    }


    getOfflineNotes(async function (notes) {


        for (const note of notes) {


            /*
            |--------------------------------------------------------------------------
            | Ne pas renvoyer les notes déjà présentes sur le serveur
            |--------------------------------------------------------------------------
            */

            if (note.is_synced === true) {

                continue;

            }


            console.log(
                "📤 Envoi de la note :",
                note
            );


            try {

                const response = await fetch(
                    '/api/offline/note',
                    {

                        method: 'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json'

                        },

                        body:
                            JSON.stringify(note)

                    }
                );


                if (response.ok) {


                    const data =
                        await response.json();


                    console.log(
                        "✅ Note synchronisée :",
                        note.local_id
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Supprimer l'ancienne note locale
                    |--------------------------------------------------------------------------
                    */

                    deleteOfflineNote(
                        note.local_id
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT
                    |
                    | On télécharge ensuite la version serveur.
                    |--------------------------------------------------------------------------
                    */

                    console.log(
                        "🆔 ID serveur :",
                        data.id
                    );


                } else {


                    console.error(
                        "❌ Erreur de synchronisation :",
                        response.status
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Afficher éventuellement la réponse Laravel
                    |--------------------------------------------------------------------------
                    */

                    try {

                        const erreur =
                            await response.json();

                        console.error(
                            "Réponse serveur :",
                            erreur
                        );

                    } catch (e) {

                        console.error(
                            "Réponse non JSON"
                        );

                    }

                }


            } catch (e) {


                console.error(
                    "❌ Impossible de contacter le serveur"
                );

                console.error(e);


            }

        }

    });

}


/*
|--------------------------------------------------------------------------
| SERVEUR → INDEXEDDB
|
| Télécharge les notes de l'utilisateur connecté.
|--------------------------------------------------------------------------
*/

async function synchroniserDepuisServeur()
{

    if (!navigator.onLine) {

        console.log(
            "📴 Pas de connexion."
        );

        return;

    }


    try {


        console.log(
            "📥 Téléchargement des notes depuis le serveur..."
        );


        const response = await fetch(
            '/api/offline/notes',
            {

                method: 'GET',

                headers: {

                    'Accept':
                        'application/json'

                }

            }
        );


        if (!response.ok) {

            throw new Error(
                "Erreur serveur : " +
                response.status
            );

        }


        const data =
            await response.json();


        console.log(
            "📦 Notes reçues du serveur :",
            data.notes
        );


        if (
            !data.success ||
            !Array.isArray(data.notes)
        ) {

            console.error(
                "❌ Format de réponse invalide"
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Transaction IndexedDB
        |--------------------------------------------------------------------------
        */

        const transaction =
            db.transaction("notes", "readwrite");


        const store =
            transaction.objectStore("notes");


        /*
        |--------------------------------------------------------------------------
        | Enregistrer chaque note
        |--------------------------------------------------------------------------
        */

        for (const note of data.notes) {


            /*
            |--------------------------------------------------------------------------
            | Une note provenant du serveur possède un ID MySQL.
            |
            | On utilise :
            |
            | local_id = server-ID
            |
            | Exemple :
            |
            | server-51
            |--------------------------------------------------------------------------
            */

            const noteLocale = {

                local_id:
                    "server-" + note.id,

                id:
                    note.id,

                server_id:
                    note.id,

                chapitre_id:
                    note.chapitre_id,

                recto:
                    note.recto,

                verso:
                    note.verso,

                nombre_revision:
                    note.nombre_revision ?? 0,

                prochaine_revision:
                    note.prochaine_revision,

                is_revised:
                    note.is_revised ?? true,

                is_synced:
                    true,

                created_at:
                    note.created_at,

                updated_at:
                    note.updated_at

            };


            store.put(noteLocale);

        }


        /*
        |--------------------------------------------------------------------------
        | Attendre la fin de la transaction
        |--------------------------------------------------------------------------
        */

        transaction.oncomplete = function () {

            console.log(
                "✅ Notes du serveur enregistrées dans IndexedDB."
            );

        };


        transaction.onerror = function (event) {

            console.error(
                "❌ Erreur transaction IndexedDB :",
                event.target.error
            );

        };


    } catch (error) {


        console.error(
            "❌ Erreur Serveur → IndexedDB :",
            error
        );

    }

}


/*
|--------------------------------------------------------------------------
| CONNEXION RETROUVÉE
|--------------------------------------------------------------------------
*/

window.addEventListener("online", function () {

    console.log("🌐 Connexion retrouvée");


    /*
    |--------------------------------------------------------------------------
    | 1. Local → Serveur
    |--------------------------------------------------------------------------
    |
    | Envoie les notes créées hors connexion.
    |
    */

    synchroniserNotes();


    /*
    |--------------------------------------------------------------------------
    | 2. Serveur → IndexedDB
    |--------------------------------------------------------------------------
    |
    | Récupère :
    |
    | - matières
    | - chapitres
    | - notes
    |
    */

    setTimeout(function () {

        synchroniserStructureDepuisServeur();

    }, 1000);

});

async function synchroniserStructureDepuisServeur()
{
    try {

        console.log("📥 Téléchargement matières + chapitres + notes...");

        const response = await fetch('/api/offline/sync', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(
                `Erreur serveur : ${response.status}`
            );
        }

        const data = await response.json();

        console.log(
            "📚 Structure reçue :",
            data
        );

        if (!data.success) {
            throw new Error(
                data.message || "Erreur de synchronisation"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        const transaction = db.transaction(
            [
                "matieres",
                "chapitres",
                "notes"
            ],
            "readwrite"
        );

        const matieresStore =
            transaction.objectStore("matieres");

        const chapitresStore =
            transaction.objectStore("chapitres");

        const notesStore =
            transaction.objectStore("notes");


        /*
        |--------------------------------------------------------------------------
        | MATIERES
        |--------------------------------------------------------------------------
        */

        for (const matiere of data.matieres) {

            matieresStore.put({

                id: matiere.id,

                matiere: matiere.matiere,

                user_id: matiere.user_id ?? null

            });


            /*
            |--------------------------------------------------------------------------
            | CHAPITRES
            |--------------------------------------------------------------------------
            */

            if (Array.isArray(matiere.chapitres)) {

                for (const chapitre of matiere.chapitres) {

                    chapitresStore.put({

                        id: chapitre.id,

                        matiere_id: matiere.id,

                        chapitre: chapitre.chapitre

                    });


                    /*
                    |--------------------------------------------------------------------------
                    | NOTES
                    |--------------------------------------------------------------------------
                    */

                    if (Array.isArray(chapitre.notes)) {

                        for (const note of chapitre.notes) {

                            notesStore.put({

                                local_id:
                                    "server-" + note.id,

                                id:
                                    note.id,

                                server_id:
                                    note.id,

                                chapitre_id:
                                    chapitre.id,

                                recto:
                                    note.recto,

                                verso:
                                    note.verso,

                                nombre_revision:
                                    note.nombre_revision ?? 0,

                                prochaine_revision:
                                    note.prochaine_revision,

                                is_revised:
                                    note.is_revised ?? true,

                                is_synced:
                                    true,

                                created_at:
                                    note.created_at,

                                updated_at:
                                    note.updated_at

                            });

                        }

                    }

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | FIN DE LA TRANSACTION
        |--------------------------------------------------------------------------
        */

        transaction.oncomplete = function () {

            console.log(
                "✅ Matières, chapitres et notes enregistrés dans IndexedDB."
            );

        };


        transaction.onerror = function (event) {

            console.error(
                "❌ Erreur IndexedDB :",
                event.target.error
            );

        };


    } catch (error) {

        console.error(
            "❌ Erreur Serveur → IndexedDB :",
            error
        );

    }
}