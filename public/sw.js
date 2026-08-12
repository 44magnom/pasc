"use strict";


/*
|--------------------------------------------------------------------------
| CONFIGURATION
|--------------------------------------------------------------------------
*/

const CACHE_NAME = "offline-cache-v8";

const OFFLINE_URL = "/offline.html";


const FILES_TO_CACHE = [

    "/offlineView/index.html",

    "/offlineView/chapitres.html",

    "/offlineView/notes.html",

    "/offlineView/creer-note.html",
    "/offlineView/breadcrumb.html",
      '/offlineView/ckeditor/ckeditor.js',
         '/offlineView/partials/brouillon-note.js',
         '/offlineView/partials/liste-brouillons.js',

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

            .then(async function (cache) {

                console.log(
                    "📦 Installation du cache :",
                    CACHE_NAME
                );


                for (const url of FILES_TO_CACHE) {

                    try {

                        const response =
                            await fetch(url);


                        if (!response.ok) {

                            console.error(
                                "❌ Fichier inaccessible :",
                                url,
                                response.status
                            );

                            continue;

                        }


                        await cache.put(
                            url,
                            response
                        );


                        console.log(
                            "✅ Mis en cache :",
                            url
                        );


                    } catch (error) {

                        console.error(
                            "❌ Erreur pour :",
                            url,
                            error
                        );

                    }

                }


                console.log(
                    "✅ Installation du cache terminée."
                );

            })

    );


    /*
    | Permet au nouveau Service Worker
    | de devenir actif immédiatement.
    */

    self.skipWaiting();

});


/*
|--------------------------------------------------------------------------
| ACTIVATE
|--------------------------------------------------------------------------
*/

self.addEventListener("activate", function (event) {

    event.waitUntil(

        caches.keys()

            .then(function (cacheNames) {

                return Promise.all(

                    cacheNames.map(
                        function (cacheName) {

                            if (
                                cacheName !== CACHE_NAME
                            ) {

                                console.log(
                                    "🗑️ Suppression ancien cache :",
                                    cacheName
                                );


                                return caches.delete(
                                    cacheName
                                );

                            }

                        }
                    )

                );

            })

            .then(function () {

                /*
                | Le nouveau Service Worker
                | prend immédiatement le contrôle
                | des pages ouvertes.
                */

                return self.clients.claim();

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
    | Navigation HTML
    |--------------------------------------------------------------------------
    */

    if (
        event.request.mode === "navigate"
    ) {

        event.respondWith(

            fetch(event.request)

                .catch(function () {

                    console.log(
                        "📴 Hors connexion :",
                        event.request.url
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Si c'est une page offlineView
                    |--------------------------------------------------------------------------
                    */

                    if (
                        event.request.url.includes(
                            "/offlineView/"
                        )
                    ) {

                        return caches.match(
                            event.request,
                            {
                                ignoreSearch: true
                            }
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Sinon afficher offline.html
                    |--------------------------------------------------------------------------
                    */

              return caches.match(
    "/offlineView/index.html"
);

                })

        );


        return;

    }


    /*
    |--------------------------------------------------------------------------
    | CSS / JS / images / autres ressources
    |--------------------------------------------------------------------------
    */

    event.respondWith(

        caches.match(
            event.request
        )

        .then(function (response) {

            if (response) {

                return response;

            }


            return fetch(
                event.request
            );

        })

    );

});