let db;

const DB_NAME = "nafarbox";
const DB_VERSION = 2;

const request = indexedDB.open(DB_NAME, DB_VERSION);

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

    if (!db.objectStoreNames.contains("notes")) {

        const store = db.createObjectStore("notes", {
            keyPath: "local_id"
        });

        store.createIndex(
            "id",
            "id",
            { unique: false }
        );

        store.createIndex(
            "chapitre_id",
            "chapitre_id",
            { unique: false }
        );

        store.createIndex(
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


request.onsuccess = function (event) {

    db = event.target.result;

    console.log("✅ Base locale prête.");

    if (navigator.onLine) {

        synchroniserNotes();

    }

};


request.onerror = function (event) {

    console.error(
        "❌ Erreur IndexedDB :",
        event.target.error
    );

};

/******************************************
 Enregistrer une note
******************************************/

function saveOfflineNote(note)
{
    console.log(note);

    const transaction = db.transaction("notes", "readwrite");

    const store = transaction.objectStore("notes");

    const request = store.add(note);

    request.onsuccess = function () {
        console.log("✅ Note enregistrée");
    };

    request.onerror = function (e) {
        console.log("❌ Erreur d'enregistrement");
        console.log(e);
    };
}



/******************************************
 Lire toutes les notes
******************************************/

function getOfflineNotes(callback)
{

    const transaction = db.transaction("notes","readonly");

    const store = transaction.objectStore("notes");

    const request = store.getAll();

    request.onsuccess = function(){

        callback(request.result);

    }

}



/******************************************
 Supprimer une note
******************************************/

function deleteOfflineNote(local_id)
{

    const transaction = db.transaction("notes","readwrite");

    const store = transaction.objectStore("notes");

    store.delete(local_id);

}

async function synchroniserNotes() {

    getOfflineNotes(async function (notes) {
        
        for (const note of notes) {
            console.log("Envoi de la note :", note);

            try {

const response = await fetch('/api/offline/note', {

    method: 'POST',

    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',

    },

    body: JSON.stringify(note)

});

                if (response.ok) {

                    console.log("Synchronisée :", note.local_id);

                    deleteOfflineNote(note.local_id);

                } else {

                    console.log("Erreur de synchronisation");

                }

            } catch (e) {

                    console.error(e.message);
    console.error(e);

            }

        }

    });

}

window.addEventListener("online", function () {

    console.log("Connexion retrouvée");

    synchroniserNotes();

});

