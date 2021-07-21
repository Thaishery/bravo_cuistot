//on défini un timeout pour s'assuré que les iframes soit bien load avant de lancer le script. 
setTimeout(() => {
    //on récupére la liste des iframe. 
    const iframe = document.querySelectorAll('iframe');
    //on initialise un tableau qui contiendra les boutons. 
    var buttons = [];
    //on boucle sur les iframes:
    for(let i = 0; i < iframe.length; i ++){
        //on récupére le bouton de l'iframe qui porte l'index i et le stoque dans la variable bouton a l'index i. 
        buttons[i] = window.frames[i].document.querySelector('button');
        //on ajout au bouton un event listener :
        buttons[i].addEventListener('click', function() {
            //on défini un timeout de 1 sec pour s'assuré de l'envoie en bdd et recharge la page:
            setTimeout(() => location.reload(), 1000);
        });
    }
    //ici 3000 corespond au timeout de chargement des iframe (3 sec. )
}, 3000);