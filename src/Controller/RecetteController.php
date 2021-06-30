<?php

namespace App\Controller;

use App\Entity\Etapes;
use App\Entity\IngredientsRecette;
use App\Entity\Recette;
use App\Entity\User;
use App\Form\EtapesType;
use App\Form\IngredientsRecetteType;
use App\Form\RecetteType;
use App\Repository\EtapesRepository;
use App\Repository\IngredientsRecetteRepository;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @Route("/recette")
 */
class RecetteController extends AbstractController
{
    /**
     * @Route("/", name="recette_index", methods={"GET"})
     */
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    //être connecter pour créer une recette! 
    /**
     * @IsGranted("ROLE_USER", message="Veuillez vous connecter ou créer un compte pour pouvoir créer une recette!")
     * @Route("/new", name="recette_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        $recette->setAuthorId($user);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('recette_new_ingredients',[
                'id' => $recette->getId(),
            ]);
        }

        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
        ]);
    }


    //ajout des ingrédients : 
    /**
     * @IsGranted("ROLE_USER", message="Veuillez vous connecter ou créer un compte pour pouvoir créer une recette!")
     * @Route("/new/{id}/ingredient", name="recette_new_ingredients", methods={"GET","POST"})
     */
    public function newIngredients(Request $request, $id, IngredientsRecetteRepository $ingredientsRecetteRepository, RecetteRepository $recetteRepository, Recette $recette): Response
    {
        //récupére l'utilisateur: 
        $user = $this->getUser();
        // initialise l'objet Ingrédients
        $ingredients = new IngredientsRecette();
        // initialise le formulaire depuis le modéle IngredientsRecetteType.
        $form = $this->createForm(IngredientsRecetteType::class, $ingredients);
        $form->handleRequest($request);
        // ajoute l'id de la recette a la class IngredientsRecette. (via l'injection de dépendances)
        $ingredients->setRecetteId($recette);
        // on récupére la liste des ingrédients de la recette pour vérifier si elle est vide ou non
        $listeIngredient = $ingredientsRecetteRepository->findByRecetteId($id);

        //si la recette a modifier a un auteur différent de l'utilisateur actuel, ne pas permetre la modification de la liste d'ingrédients:
        if ($recette->getAuthorId()->getId() !== $user->getId()){
            // forbiden.html.twig => a modifier (placeholder atm)
            return $this->render('errors/forbiden.html.twig',[
                'authorid' => $recette,
                'userid' => $user->getId(),
            ]);
        }
        // si il y a deja des ingrédients de recette
        else if($listeIngredient != null){
            //traitement formulaire 
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ingredients);
                $entityManager->flush();
                
                return $this->redirectToRoute('recette_new_ingredients',[
                    'id' => $id,
                ]);
            }
            //on affiche le deuxieme template avec le bouton étape suivante.
            return $this->render('recette/new_ingredients_suite.html.twig', [
                'id'=> $id,
                'listeIngredients' => $listeIngredient,
                'ingredients' => $ingredients,
                'form' => $form->createView(),
            ]);
        }
        else {
            //traitement formulaire
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ingredients);
                $entityManager->flush();
                return $this->redirectToRoute('recette_new_ingredients',[
                    'id' => $id,
                ]);
            }
            //il n'y as pas encore d'ingrédients a la recette, on affiche donc le template initiale. 
            return $this->render('recette/new_ingredients.html.twig', [
                'id'=> $id,
                'ingredients' => $ingredients,
                'form' => $form->createView(),
            ]);
        }
    }


    //ajout des étapes : 
    /**
     * @IsGranted("ROLE_USER", message="Veuillez vous connecter ou créer un compte pour pouvoir créer une recette!")
     * @Route("/new/{id}/etapes", name="recette_new_etapes", methods={"GET","POST"})
     */
    public function newEtapes(Request $request, $id,EtapesRepository $etapesRepository, IngredientsRecetteRepository $ingredientsRecetteRepository, RecetteRepository $recetteRepository, Recette $recette): Response
    {
        //récupére l'utilisateur: 
        $user = $this->getUser();
        // initialise l'objet Etapes
        $etape = new Etapes();
        // initialise le formulaire depuis le modéle EtapesType.
        $form = $this->createForm(EtapesType::class, $etape);
        $form->handleRequest($request);
        // on récupére la liste des étapes de la recette pour vérifier si elle est vide ou non
        $listeEtapes = $etapesRepository->findByRecetteId($id);
        // ajout de l'id de la recette a la class Etapes. (via l'injection de dépendances)
        $etape->setRecetteId($recette);
        // TODO : trouver un moyen d'incrémenter de 1 le numéro de l'étape automatiquement pour chaque nouvelle étape.
        // TODO : a faire dans les conditions qui suivent => si $listeEtapes != null et else. 
        

        //si la recette a modifier a un auteur différent de l'utilisateur actuel, ne pas permetre la modification de la liste d'étape:
        if ($recette->getAuthorId()->getId() !== $user->getId()){
            // forbiden.html.twig => a modifier (placeholder atm) les paramétres pouront être suprimer par la suite. 
            return $this->render('errors/forbiden.html.twig',[
                'authorid' => $recette,
                'userid' => $user->getId(),
            ]);
        }
        // si il y a deja des ingrédients de recette
        else if($listeEtapes != null){
            //todo : on utilise la fonction count sur notre tableau listeEtapes et on ajoute 1 a celui ci. 

            $etape->setIsNumber(count($listeEtapes)+1);
            //traitement formulaire 
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($etape);
                $entityManager->flush();
                
                return $this->redirectToRoute('recette_new_etapes',[
                    'id' => $id,
                ]);
            }
            //on affiche le deuxieme template avec le bouton étape suivante.
            return $this->render('recette/new_etapes_suite.html.twig', [
                'listeEtapes' => $listeEtapes,
                'etape' => $etape,
                'form' => $form->createView(),
            ]);
        }
        //listeEtape est null => il n'y a donc pas encore d'étape! 
        else {
            // todo: listeEtape est vide, on peut donc passer is_number a 1 .
            $etape->setIsNumber(1);
            //traitement formulaire
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($etape);
                $entityManager->flush();
                return $this->redirectToRoute('recette_new_etapes',[
                    'id' => $id,
                ]);
            }
            //il n'y as pas encore d'étapes a la recette, on affiche donc le template initiale. 
            return $this->render('recette/new_etapes.html.twig', [
                'etape' => $etape,
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/{id}", name="recette_show", methods={"GET"})
     */
    public function show(Recette $recette): Response
    {
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }

    //a éditer pour que seul l'utilisateur qui a créer la recette puisse la modifier, ou que l'admin puisse (l'admin devra probablement utiliser /admin/administration)
    /**
     * @Route("/{id}/edit", name="recette_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recette $recette): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recette_index');
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
        ]);
    }

    // a éditer aussi pour éviter la suppréssion des recette des autres. 
    /**
     * @Route("/{id}", name="recette_delete", methods={"POST"})
     */
    public function delete(Request $request, Recette $recette): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recette_index');
    }
}
