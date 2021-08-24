<?php

namespace App\Controller;

use App\Form\RechercheType;

use App\Repository\IngredientsRecetteRepository;
use App\Repository\PlatsRepository;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RechercheController extends AbstractController {
    /**
     * @Route("/recherche", name="recherche")
     */
     public function index(Request $request, RecetteRepository $recetteRepository, IngredientsRecetteRepository $ingredientsRecetteRepository ): Response {

         $form = $this -> createForm(RechercheType::class);
         $form -> handleRequest($request);

        //  $touteslesrecettes = $recetteRepository -> findAll();

         if ($form->isSubmitted() && $form->isValid()) {

             // ? On récupère les données du formulaire saisi par l'utilisateur

             $niveau = $form -> get ('niveau') ->getData() ;
             $plats = $form -> get ('plats') ->getData() ;
             $recette = $form -> get ('recette') ->getData() ;
             $alimentation = $form -> get ('alimentation') ->getData() ;
             $ingredient = $form -> get ('ingredient') ->getData() ;
             $cuisson = $form -> get ('cuisson') ->getData() ;


                 // ? Si le champ "niveau" est rempli dans le formulaire,  
                 if ($niveau != null) {

                     // ? On fait une requete sur le repository pour trouver les recettes correspondante, puis on les stock dans un tableau "$listeRecetteParNiveau"  
                     $listeRecetteParNiveau = $recetteRepository -> findByRecetteNiveau($niveau);
                     //  dd($listeRecetteParNiveau);
                 }

                     //  ? Si le champ "niveau" n'est pas rempli dans le formulaire
                     if (!$niveau) { 

                         // ? On récupère toutes les recettes, on filtre ensuite via la fonction "array_intersect"  
                         $listeRecetteParNiveau = $recetteRepository -> findAll(); 
                     }

                 // ? Si le champ "plats" est rempli dans le formulaire
                 if ($plats) {
                     $listeRecetteParTypeDePlats = $recetteRepository -> findByTypeDePlats ($plats);
                    //  dd($listeRecetteParTypeDePlats);
                 }

                     //  ? Si le champ "plats" n'est pas rempli dans le formulaire
                     if (!$plats) { 
                         $listeRecetteParTypeDePlats = $recetteRepository -> findAll(); 
                     }

                 // ? Si le champ "recette" est rempli dans le formulaire     
                 if ($recette) {
                     $listeRecetteParNom = $recetteRepository -> findByName ($recette);
                    //  dd($listeRecette);
                 }

                     //  ? Si le champ "plats" n'est pas rempli dans le formulaire
                     if (!$recette) { 
                         $listeRecetteParNom = $recetteRepository -> findAll();  
                     }

                 // ? Si le champ "alimentation" est rempli dans le formulaire      
                 if ($alimentation) {
                     $listeRecetteParTypeAlimentation = $recetteRepository -> findByTypeAlimentation ($alimentation);
                     // dd($listeRecetteParTypeAlimentation );
                 }

                     //  ? Si le champ "alimentation" n'est pas rempli dans le formulaire
                     if (!$alimentation) { 
                         $listeRecetteParTypeAlimentation = $recetteRepository -> findAll(); 
                     }

                 // ? Si le champ "ingredient" est rempli dans le formulaire      
                 if ($ingredient) {
                     $listeRecetteParIngredientsRepository = $ingredientsRecetteRepository -> findByIngredients ($ingredient);
                    //  dd($listeRecetteParIngredientsRepository );
                      
                     // ? on déclare un tableau vide  
                     $listeRecetteParIngredients = null ;

                     // ? On boucle sur "$listeRecetteParIngredientsRepository", 
                     for ($i=0; $i < count ($listeRecetteParIngredientsRepository); $i++ ) {

                         //  ? On stocke la recette via son "id" dans la variable "$recette_id"
                         $recette_id = $listeRecetteParIngredientsRepository[$i]-> getRecetteId();
                         //  dd($recette_id);

                         //  ? On ajoute la recette contenue dans "$recette_id" au tableau "$listeRecetteParIngredients"
                         $listeRecetteParIngredients [$i] = $recette_id;

                         //  ? On doit effectuer cela car on ne peut pas accéder à la liste des ingrédients, depuis le repository "$recetteRepository"

                     }

                      //  dd($listeRecetteParIngredients);

                 }  

                     if (!$ingredient) { 
                         $listeRecetteParIngredients = $recetteRepository -> findAll(); 
                     }

                 if ($cuisson) {
                     $listeRecetteParTypeCuisson = $recetteRepository -> findByCuisson ($cuisson);
                     // dd($listeRecetteParTypeCuisson );
                 }      
                 
                     if (!$cuisson) { 
                         $listeRecetteParTypeCuisson = $recetteRepository -> findAll(); 
                     }

                 // ? On crée une variable "$resultat", qui stockeras uniquement les recettes présente dans tout les tableaux   
                 $resultat = array_intersect( $listeRecetteParNom ,$listeRecetteParTypeCuisson,$listeRecetteParIngredients,$listeRecetteParNiveau,$listeRecetteParTypeAlimentation,$listeRecetteParTypeDePlats);
                 //  dd ($resultat);               

                     if ($resultat) {

                         return $this -> render ('recette/index.html.twig',[
                             'recettes' => $resultat
                         ]);

                     };

                     
                     if (!$resultat) {

                        return $this -> render('recherche/index.html.twig', [
                            'controller_name' => 'RechercheController',
                            'form' => $form -> createView(), 
                        ]);

                    };

         }    

         return $this->render('recherche/index.html.twig', [
             'controller_name' => 'RechercheController',
             'form' => $form -> createView(), 
         ]);

     }

 }
