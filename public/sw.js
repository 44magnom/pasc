"use strict";

const CACHE_NAME = "offline-cache-v4";
const OFFLINE_URL = "/offline.html";

const FILES_TO_CACHE = [
    OFFLINE_URL
];

self.addEventListener("install", (event) => {

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(FILES_TO_CACHE))
            .then(() => self.skipWaiting())
    );

});

self.addEventListener("activate", (event) => {

    event.waitUntil(
        caches.keys().then(cacheNames => {

            return Promise.all(
                cacheNames.map(cacheName => {

                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }

                })
            );

        }).then(() => self.clients.claim())
    );

});

self.addEventListener("fetch", (event) => {

    // Ne pas intercepter les API
    if (event.request.url.includes("/api/")) {
        return;
    }

    event.respondWith(

        fetch(event.request)
            .then(response => {

                // Mettre en cache les pages GET réussies
                if (
                    event.request.method === "GET" &&
                    response.status === 200
                ) {

                    const clone = response.clone();

                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, clone);
                    });

                }

                return response;

            })
            .catch(() => {

                return caches.match(event.request)
                    .then(cachedResponse => {

                        if (cachedResponse) {
                            return cachedResponse;
                        }

                        if (event.request.mode === "navigate") {
                            return caches.match(OFFLINE_URL);
                        }

                    });

            })

    );

});