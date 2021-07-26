console.log('Hello World !');

const buttonMinus = document.getElementById('buttonMinusConvives');
const buttonPlus = document.getElementById('buttonPlusConvives');
let nombreDeConvives =  document.getElementById('nombreDeConvives');
buttonMinus.addEventListener('click', function(){
    if (nombreDeConvives.innerHTML > 1){
        nombreDeConvives.innerHTML = nombreDeConvives.innerHTML-1;
        console.log(nombreDeConvives.innerHTML);
    }
});
buttonPlus.addEventListener('click', function(){
    if (nombreDeConvives.innerHTML < 99){
        nombreDeConvives.innerHTML = parseInt(nombreDeConvives.innerHTML)+1;
        console.log(nombreDeConvives.innerHTML);
    }
});
let ingredientsQuantite = document.getElementsByClassName('ingredientsQuantite');
let ingredientsQuantitePourUnePersonne = [];
console.log(ingredientsQuantite);
for(let i=0; i< ingredientsQuantite.length; i++){
    ingredientsQuantitePourUnePersonne[i] = ingredientsQuantite[i].innerHTML / nombreDeConvives.innerHTML;
}
console.log(ingredientsQuantitePourUnePersonne);
