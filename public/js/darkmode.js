 //  ? Feuille de script du DarkMode / Light Mode

     //  ! 1 ère Méthode 

         // * window.matchMedia vas vérifier si notre document possède déjà des mediaQuery (retourne=true)
         // * window.matchMedia vérifie s'il y a des mediaQuery intégré à notre CSS et leur contenu
            //  if (  window.matchMedia && window.matchMedia
            //          ('(prefers-color-scheme: dark)').matches ) {

            //             alert ('Je suis en dark mode')
            //          }

     //  ! 2 ème Méthode 

         //  function themeNuitJour () {

         //      // * L'objet date en javascript, donne la date  
         //      const date = new Date ()
         //      // * L'objet getHours en javascript, donne l'heure  
         //      const hour = date.getHours ()

         //      //  *si on est strictement supérieur à 5h 
         //      //  * ou si on est strictement inférieur à 20h
         //      //  * On dit que c'est la journée

         //          if ( hour > 5 || hour < 20) {

         //              // * On utilise 'document.documentElement.style.setProperty' pour accéder au propriétés Javascript/Variables

         //                   // ? Lorsqu'on est en Journée (LightMode)
                               
         //                       document.documentElement.style.setProperty ('--ecriture','#333')
         //                       document.documentElement.style.setProperty ('--fond','#f1f1f1')

         //          }

         //          else {

         //                   // ? Lorsqu'on est en Journée (LightMode)
                                       
         //                      document.documentElement.style.setProperty ('--ecriture','#f1f1f1')
         //                      document.documentElement.style.setProperty ('--fond','#333')

         //          }

         //  }

         //  themeNuitJour ()

     //  ! 3 ème Méthode 

        //  const btnToggle = document.querySelector('.btn-toggle');

        //  btnToggle.addEventListener('click', () => {
         
        //      const body = document.body;
         
        //      if(body.classList.contains('dark')){
         
        //          body.classList.add('light')
        //          body.classList.remove('dark')
        //          btnToggle.innerHTML = "Go Dark"
         
        //      } else if(body.classList.contains('light')){
         
        //          body.classList.add('dark')
        //          body.classList.remove('light')
        //          btnToggle.innerHTML = "Go Light"
         
        //      }
         
        //  })
