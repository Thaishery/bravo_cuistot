// on récupère nos boutons dans des variables JS 
const buttonMinus = document.getElementById('buttonMinusConvives');
const buttonPlus = document.getElementById('buttonPlusConvives');
// on récupère le nombre de convives et on le stock dans une variable
let nombreDeConvives =  document.getElementById('nombreDeConvives');
// on ajoute un écouteur d'évènement 'click' à notre bouton moins
buttonMinus.addEventListener('click', function(){
// si il y a plus de 1 convive 
    if (nombreDeConvives.innerHTML > 1){
// lors du click on retire 1 au nombre de convives
        nombreDeConvives.innerHTML = nombreDeConvives.innerHTML-1;
// on appelle notre fonction refreshIngredients pour mettre à jour les quantités de chaque ingrédient
    refreshIngredients();
    }
});
// on ajoute un écouteur d'évènement 'click' à notre bouton plus
buttonPlus.addEventListener('click', function(){
// s'il y a moins de 99 convives on ajoute 1
    if (nombreDeConvives.innerHTML < 99){
// lors du click on ajoute 1 au nombre de convives
        nombreDeConvives.innerHTML = parseInt(nombreDeConvives.innerHTML)+1;
// on appelle notre fonction refreshIngredients pour mettre à jour les quantités de chaque ingrédient
    refreshIngredients();
    }
});
// on récupère les quantités de chaque ingrédient
let ingredientsQuantite = document.getElementsByClassName('ingredientsQuantite');
// on déclare un tableau qui stockera les quantités pour une personne
let ingredientsQuantitePourUnePersonne = [];
// on boucle sur le tableau ingredientsQuantite
for(let i=0; i< ingredientsQuantite.length; i++){
// pour chaque ingrédient on calcule la quantité de celui-ci pour une personne
    ingredientsQuantitePourUnePersonne[i] = ingredientsQuantite[i].innerHTML / nombreDeConvives.innerHTML; 
}
// on déclare une fonction refreshIngredients() qui mettra à jour les quantités lors d'appui sur les boutons
function refreshIngredients() {
// on boucle sur le tableau ingredientsQuantite 
    for(let i=0; i < ingredientsQuantite.length; i++)   {
// pour chaque ingrédient de la liste on multiplie la quantité pour une personne au nombre de convives
        ingredientsQuantite[i].innerHTML = ingredientsQuantitePourUnePersonne[i] * nombreDeConvives.innerHTML;
        } 
}
