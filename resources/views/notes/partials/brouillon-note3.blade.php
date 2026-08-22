<script>

document.addEventListener('DOMContentLoaded', function(){


console.log("🚀 Brouillon-note2 chargé");



let db = null;



const request = indexedDB.open(
    "nafarbox",
    7
);



request.onsuccess = function(event){


    db = event.target.result;


    console.log(
        "✅ Base brouillon ouverte"
    );



    initialiserBrouillon();


};





function getChapitreId(){


    return Number(
        document.getElementById('chapitre_id')?.value
    );


}





function initialiserBrouillon(){


    const chapitreId =
        getChapitreId();



    if(!chapitreId){


        console.log(
            "❌ chapitre introuvable"
        );


        return;

    }



    chargerBrouillon();



    setInterval(
        sauvegarderBrouillon,
        3000
    );



    console.log(
        "✅ Système brouillon prêt"
    );

}





function sauvegarderBrouillon(){


    const chapitreId =
        getChapitreId();



    if(!chapitreId || !db){

        return;

    }



    const recto =
        document.getElementById('recto')?.value || '';



    const verso =
        window.editorVerso
        ?
        window.editorVerso.getData()
        :
        '';




    if(
        recto.trim()==='' &&
        verso.trim()===''
    ){

        return;

    }





    const brouillon = {


        id:
        "brouillon_note_"+chapitreId,


        chapitre_id:
        chapitreId,


        recto:
        recto,


        verso:
        verso,


        updated_at:
        new Date().toISOString()


    };




    const transaction =
        db.transaction(
            "brouillons",
            "readwrite"
        );



    transaction
    .objectStore("brouillons")
    .put(brouillon);



    console.log(
        "💾 Brouillon sauvegardé",
        brouillon
    );


}





function chargerBrouillon(){


    const chapitreId =
        getChapitreId();



    const transaction =
        db.transaction(
            "brouillons",
            "readonly"
        );



    const store =
        transaction.objectStore(
            "brouillons"
        );



    const request =
        store.get(
            "brouillon_note_"+chapitreId
        );




    request.onsuccess=function(){


        const brouillon =
            request.result;



        if(!brouillon){

            console.log(
                "📝 Aucun brouillon"
            );

            return;

        }




        document.getElementById('recto').value =
            brouillon.recto;



        if(window.editorVerso){

            window.editorVerso.setData(
                brouillon.verso
            );

        }



        console.log(
            "📝 Brouillon restauré",
            brouillon
        );


    };


}





window.supprimerBrouillon = function(){


    const chapitreId =
        getChapitreId();



    if(!db || !chapitreId){

        return;

    }



    const transaction =
        db.transaction(
            "brouillons",
            "readwrite"
        );



    transaction
    .objectStore("brouillons")
    .delete(
        "brouillon_note_"+chapitreId
    );



    console.log(
        "🗑️ Brouillon supprimé"
    );


}





});


</script>