<?php

namespace App\Controller;

use App\Entity\IngredientsRecette;
use App\Entity\Recette;
use App\Entity\User;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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
                'id' => $this->$recette->getId(),
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
     * @Route("/new/{id}/ingredients", name"recette_new_ingredients", methods={"GET","POST"})
     */
    public function newIngredient(Request $request, $id): Response
    {
        //récupére l'utilisateur: 
        $user = $this->getUser();
        // initialise l'objet ingrédients
        $ingredients = new IngredientsRecette();
        // initialise l'objet recette $id (passer en paramétre d'url depuis "recette_new")
        $recette = new Recette($id);
        // initialise le formulaire depuis le modéle IngredientsREcetteType (A CRéER)
        $form = $this->createForm(IngredientsRecetteType::class, $ingredients);
        $form->handleRequest($request);
        // ajoute l'id de la recette a la class IngredientsRecette. (peut etre que $id n'est pas bon, tester $recette dans ce cas pour envoyer un objet et non une string)
        $ingredients->setRecetteId($id);

        //si la recette a modifier a un auteur différent de l'utilisateur actuel, ne pas permetre la modification de la liste d'ingrédients:
        if ($recette->getAuthorId() != $user->getId()){
            // forbiden.html.twig => a modifier (placeholder atm)
            return $this->render('forbiden.html.twig');
        }
        else {        
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ingredients);
                $entityManager->flush();
                //prévoir dans le template une conditions si +d'1 ingrédients alors passer a la suite. ou un deuxieme template avec le bouton étape suivante.
                return $this->redirectToRoute('recette_new_ingredients',[
                    'id' => $id,
                ]);
            }
            return $this->render('recette/new_ingredients.html.twig', [
                'ingredients' => $ingredients,
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
