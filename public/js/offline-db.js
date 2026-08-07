let db;

const request = indexedDB.open("nafarbox", 1);

request.onupgradeneeded = function (event) {

    db = event.target.result;

    if (!db.objectStoreNames.contains("notes")) {

        const store = db.createObjectStore("notes", {
            keyPath: "local_id"
        });

        store.createIndex("is_synced", "is_synced");
    }

};

request.onsuccess = function (event) {

    db = event.target.result;

    console.log("Base locale prête.");

    if (navigator.onLine) {
        synchroniserNotes();
    }

};

request.onerror = function () {

    console.log("Erreur IndexedDB");

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

