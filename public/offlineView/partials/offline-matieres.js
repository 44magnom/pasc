"use strict";


(function () {


    const TABLE_NAME = "matieres";

    const ACTION_CREATE = "create";

    const ENDPOINT =
        "/api/offline/matiere";


    let synchronisationEnCours = false;



    /*
    |--------------------------------------------------------------------------
    | ATTENDRE INDEXEDDB
    |--------------------------------------------------------------------------
    */

    function attendreDB() {

        return new Promise(function(resolve, reject){


            if (window.offlineDB) {

                resolve(
                    window.offlineDB
                );

                return;
            }



            let timeout;



            function dbPrete(){


                clearTimeout(timeout);


                window.removeEventListener(
                    "offlineDBReady",
                    dbPrete
                );


                if(window.offlineDB){

                    resolve(
                        window.offlineDB
                    );

                }
                else{

                    reject(
                        new Error(
                            "IndexedDB indisponible"
                        )
                    );

                }

            }



            window.addEventListener(
                "offlineDBReady",
                dbPrete
            );



            timeout =
                setTimeout(function(){

                    window.removeEventListener(
                        "offlineDBReady",
                        dbPrete
                    );


                    reject(
                        new Error(
                            "Timeout IndexedDB"
                        )
                    );


                },10000);


        });

    }





    /*
    |--------------------------------------------------------------------------
    | RÉCUPÉRER LES MATIÈRES À SYNCHRONISER
    |--------------------------------------------------------------------------
    */

    async function recupererMatieresEnAttente(){


        const db =
            await attendreDB();



        const transaction =
            db.transaction(
                "sync_queue",
                "readonly"
            );


        const store =
            transaction.objectStore(
                "sync_queue"
            );


        const request =
            store.getAll();



        return new Promise(function(resolve,reject){


            request.onsuccess =
                function(){


                    const elements =
                        request.result || [];



                    const matieres =
                        elements.filter(function(item){


                            return (

                                item.table === TABLE_NAME

                                &&

                                item.action === ACTION_CREATE

                            );


                        });



                    console.log(
                        "📚 Matières en attente :",
                        matieres
                    );



                    resolve(
                        matieres
                    );


                };



            request.onerror =
                function(event){

                    reject(
                        event.target.error
                    );

                };


        });


    }





    /*
    |--------------------------------------------------------------------------
    | ENVOYER UNE MATIÈRE AU SERVEUR
    |--------------------------------------------------------------------------
    */

    async function envoyerMatiere(element){


        const matiere =
            element.data;



        console.log(
            "📤 Envoi matière :",
            matiere
        );



        try{


            const response =
                await fetch(
                    ENDPOINT,
                    {

                        method:"POST",


                        credentials:
                            "same-origin",



                        headers:{


                            "Content-Type":
                                "application/json",


                            "Accept":
                                "application/json"


                        },



                        body:
                            JSON.stringify({


                                local_id:
                                    element.local_id,



                                matiere:
                                    matiere.matiere,



                                /*
                                IMPORTANT :
                                Laravel utilise cet ID
                                pour retrouver l'utilisateur
                                */

                                user_id:
                                    matiere.user_id


                            })

                    }
                );




            const data =
                await response.json();



            console.log(
                "📥 Réponse Laravel :",
                data
            );



            if(!response.ok){


                console.error(
                    "❌ Erreur serveur :",
                    data
                );


                return false;

            }




            if(
                !data.success ||
                !data.matiere
            ){

                console.error(
                    "❌ Réponse invalide",
                    data
                );


                return false;

            }





            await remplacerMatiereLocale(
                element,
                data.matiere
            );




            await supprimerQueue(
                element.id
            );



            console.log(
                "✅ Matière synchronisée :",
                data.matiere
            );



            return true;



        }
        catch(error){


            console.error(
                "❌ Erreur envoi matière :",
                error
            );


            return false;

        }



    }





    /*
    |--------------------------------------------------------------------------
    | REMPLACER ID LOCAL PAR ID SERVEUR
    |--------------------------------------------------------------------------
    */

    function remplacerMatiereLocale(
        element,
        serveur
    ){


        return attendreDB()
        .then(function(db){


            return new Promise(function(resolve,reject){



                const transaction =
                    db.transaction(
                        "matieres",
                        "readwrite"
                    );



                const store =
                    transaction.objectStore(
                        "matieres"
                    );



                store.delete(
                    element.local_id
                );



                store.put({


                    id:
                        serveur.id,


                    matiere:
                        serveur.matiere,


                    user_id:
                        serveur.user_id,


                    is_synced:
                        true


                });



                transaction.oncomplete =
                    function(){


                        console.log(
                            "✅ Matière locale remplacée"
                        );


                        resolve();

                    };



                transaction.onerror =
                    function(event){

                        reject(
                            event.target.error
                        );

                    };



            });


        });


    }





    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER DE SYNC_QUEUE
    |--------------------------------------------------------------------------
    */

    function supprimerQueue(id){


        return attendreDB()
        .then(function(db){



            return new Promise(function(resolve,reject){



                const transaction =
                    db.transaction(
                        "sync_queue",
                        "readwrite"
                    );



                const store =
                    transaction.objectStore(
                        "sync_queue"
                    );



                const request =
                    store.delete(id);



                request.onsuccess =
                    function(){


                        console.log(
                            "🗑️ Supprimé de sync_queue :",
                            id
                        );


                        resolve();

                    };



                request.onerror =
                    function(event){

                        reject(
                            event.target.error
                        );

                    };


            });


        });


    }





    /*
    |--------------------------------------------------------------------------
    | SYNCHRONISATION
    |--------------------------------------------------------------------------
    */

    async function synchroniserMatieres(){



        if(synchronisationEnCours){

            return;

        }



        if(!navigator.onLine){

            console.log(
                "📴 Pas de connexion"
            );


            return;

        }



        synchronisationEnCours = true;



        try{


            const matieres =
                await recupererMatieresEnAttente();



            if(matieres.length === 0){


                console.log(
                    "✅ Aucune matière à synchroniser"
                );


                return;

            }



            for(
                const element of matieres
            ){

                await envoyerMatiere(
                    element
                );

            }



        }
        catch(error){


            console.error(
                "❌ Erreur synchronisation matières :",
                error
            );


        }
        finally{


            synchronisationEnCours = false;


        }



    }





    /*
    |--------------------------------------------------------------------------
    | DISPONIBLE GLOBALEMENT
    |--------------------------------------------------------------------------
    */

    window.synchroniserMatieres =
        synchroniserMatieres;




    /*
    |--------------------------------------------------------------------------
    | RETOUR INTERNET
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        "online",
        function(){


            console.log(
                "🌐 Connexion retrouvée - matières"
            );


            setTimeout(
                synchroniserMatieres,
                500
            );


        }
    );



})();

/*
|--------------------------------------------------------------------------
| Vérifier au chargement si une synchronisation est nécessaire
|--------------------------------------------------------------------------
*/

window.addEventListener(
    "offlineDBReady",
    function(){

        console.log(
            "✅ DB prête - vérification matières"
        );


        if(navigator.onLine){

            setTimeout(
                function(){

                    synchroniserMatieres();

                },
                1000
            );

        }

    }
);