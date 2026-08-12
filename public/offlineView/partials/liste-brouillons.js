(function () {


const DB_NAME = "nafarbox";
const DB_VERSION = 7;
const STORE_NAME = "brouillons";



async function ouvrirDBBrouillons(){


    return new Promise((resolve,reject)=>{


        const request =
            indexedDB.open(
                DB_NAME,
                DB_VERSION
            );


        request.onsuccess = function(e){

            resolve(
                e.target.result
            );

        };


        request.onerror = function(e){

            reject(
                e.target.error
            );

        };


    });


}





async function afficherBrouillons(){


    const container =
        document.getElementById(
            "listeBrouillonsContainer"
        );


    if(!container){

        return;

    }



    try {


        const db =
            await ouvrirDBBrouillons();



        if(
            !db.objectStoreNames.contains(
                STORE_NAME
            )
        ){

            console.log(
                "Aucun store brouillons"
            );

            return;

        }



        const transaction =
            db.transaction(
                STORE_NAME,
                "readonly"
            );


        const store =
            transaction.objectStore(
                STORE_NAME
            );


        const request =
            store.getAll();



        request.onsuccess =
            function(){


                const brouillons =
                    request.result;



                if(
                    brouillons.length === 0
                ){

                    container.innerHTML = "";

                    return;

                }




                let html = `

                <div class="card mb-4"
                     style="background:#F8F3EB;border:1px solid #D2B48C;">


                    <div class="card-body">

                    <h5 style="color:#654321;">
                        📝 Brouillons
                    </h5>

                `;



                brouillons.forEach(
                    function(brouillon){



                    html += `

                    <div class="border rounded p-3 mb-2"
                         style="background:#FFFDF9;">


                        <div style="color:#654321;font-weight:bold;">

                            ${brouillon.recto || "Sans titre"}

                        </div>


                        <small class="text-muted">

                            Modifié :
                            ${new Date(brouillon.updated_at)
                            .toLocaleString()}

                        </small>


                        <br>


                        <a href="/offlineView/creer-note.html?id=${brouillon.chapitre_id}"
                           class="btn btn-sm mt-2"
                           style="background:#F8F3EB;color:#654321;border:1px solid #D2B48C;">


                            Continuer

                        </a>


                    </div>

                    `;



                    }

                );



                html += `

                    </div>
                </div>

                `;



                container.innerHTML =
                    html;



            };




    }catch(error){


        console.error(
            "❌ Erreur récupération brouillons :",
            error
        );


    }


}





document.addEventListener(
    "DOMContentLoaded",
    afficherBrouillons
);



})();