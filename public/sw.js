"use strict";

const CACHE_NAME = "offline-cache-v7";

const OFFLINE_URL = "/offline.html";

const FILES_TO_CACHE = [
    "/offlineView/index.html",
    "/offlineView/chapitres.html",
    "/offlineView/notes.html",
    "/offlineView/creer-note.html",
    "/offline.html"
];


/*
|--------------------------------------------------------------------------
| INSTALL
|--------------------------------------------------------------------------
*/

self.addEventListener("install", function (event) {

    event.waitUntil(

        caches.open(CACHE_NAME)

            .then(function (cache) {

                console.log(
                    "📦 Mise en cache des pages offline..."
                );

                return cache.addAll(
                    FILES_TO_CACHE
                );

            })

    );

});


/*
|--------------------------------------------------------------------------
| ACTIVATE
|--------------------------------------------------------------------------
*/

self.addEventListener("activate", function (event) {

    event.waitUntil(

        caches.keys().then(function (cacheNames) {

            return Promise.all(

                cacheNames.map(function (cacheName) {

                    if (cacheName !== CACHE_NAME) {

                        console.log(
                            "🗑️ Suppression ancien cache :",
                            cacheName
                        );

                        return caches.delete(
                            cacheName
                        );

                    }

                })

            );

        })

    );

});


/*
|--------------------------------------------------------------------------
| FETCH
|--------------------------------------------------------------------------
*/

self.addEventListener("fetch", function (event) {

    /*
    |--------------------------------------------------------------------------
    | Pages HTML
    |--------------------------------------------------------------------------
    */

    if (event.request.mode === "navigate") {

        event.respondWith(

            fetch(event.request)

                .catch(function () {

                    console.log(
                        "📴 Hors connexion :",
                        event.request.url
                    );


                    /*
                    | Si l'utilisateur demande directement
                    | offlineView/index.html
                    */

                    if (
                        event.request.url.includes(
                            "/offlineView/"
                        )
                    ) {

                        return caches.match(
                            event.request
                        );

                    }


                    /*
                    | Sinon, afficher notre page offline
                    */

                    return caches.match(
                        OFFLINE_URL
                    );

                })

        );

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | CSS / JS / autres fichiers
    |--------------------------------------------------------------------------
    */

    event.respondWith(

        caches.match(
            event.request
        )

        .then(function (response) {

            return response ||
                   fetch(event.request);

        })

    );

});