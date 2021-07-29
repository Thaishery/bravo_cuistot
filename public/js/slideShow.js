 // console.log('HelloWorld!');
 
 const flechesGauche = document.getElementById ('flechesDirectionnellesGauche');
 const flechesDroite = document.getElementById ('flechesDirectionnellesGauche');

 const nombreEtapes = document.getElementsByClassName ('numeroEtapes');
 //  const nomEtapes = document.getElementsByClassName ('etapeName');
 //  const descriptionEtapes = document.getElementsByClassName ('etapeDescription');
 //  const imageEtapes = document.getElementsByClassName ('etapeImage');
 
 //   console.log (imageEtapes);

     function creerEtapes (numeroEtapes, etapeName, etapeDescription) {
     
         var etapes = {};
         etapes.numero = numeroEtapes; 
         etapes.name = etapeName;
         etapes.description = etapeDescription;
         
        //  if (etapeImage) {
        //      etapes.image = etapeImage;
        //  }

        //  else {
        //      etapes.image = null;
        //  }
         
     }

     var listeEtapes = [];
    
     for (let i = 0; i < nombreEtapes.length; i++ ) {

         let numeroEtapes = i+1;
         let nomEtapes = document.getElementsByClassName ('etapeName') [i].innerHTML;
         let descriptionEtapes = document.getElementsByClassName ('etapeDescription') [i].innerHTML;

         //  console.log (nomEtapes);

         listeEtapes [i] = new creerEtapes (numeroEtapes,descriptionEtapes,nomEtapes)
         
     }

     console.log (listeEtapes);

    