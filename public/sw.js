"use strict";

const CACHE_NAME = "offline-cache-v3";
const OFFLINE_URL = "/offline.html";

// Fichiers indispensables
const FILES_TO_CACHE = [
    "/offline.html",
    "/js/offline-db.js",
    "/css/app.css",
    "/js/app.js"
];

// Installation
self.addEventListener("install", (event) => {

    self.skipWaiting();

    event.waitUntil(

        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(FILES_TO_CACHE))

    );

});

// Activation
self.addEventListener("activate", (event) => {

    event.waitUntil(

        caches.keys().then(keys => {

            return Promise.all(

                keys.map(key => {

                    if (key !== CACHE_NAME) {

                        return caches.delete(key);

                    }

                })

            );

        })

    );

    self.clients.claim();

});

// Requêtes
self.addEventListener("fetch", (event) => {

    // On ne met jamais l'API en cache
    if (event.request.url.includes("/api/")) {

        event.respondWith(fetch(event.request));

        return;

    }

    event.respondWith(

        caches.match(event.request)

            .then(cachedResponse => {

                // Si la ressource est déjà dans le cache
                if (cachedResponse) {

                    return cachedResponse;

                }

                // Sinon on va la chercher sur Internet
                return fetch(event.request)

                    .then(networkResponse => {

                        // On ne met en cache que les requêtes GET réussies
                        if (
                            event.request.method === "GET" &&
                            networkResponse.status === 200
                        ) {

                            const clone = networkResponse.clone();

                            caches.open(CACHE_NAME)

                                .then(cache => {

                                    cache.put(event.request, clone);

                                });

                        }

                        return networkResponse;

                    })

                    .catch(() => {

                        // Si c'est une navigation HTML
                        if (event.request.mode === "navigate") {

                            return caches.match(OFFLINE_URL);

                        }

                    });

            })

    );

});