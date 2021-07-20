<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientsUserType;
use App\Repository\IngredientsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/profile/ingredient")
 */
class UserIngredientController extends AbstractController
{
    /**
     * @Route("/", name="user_ingredient_index", methods={"GET"})
     */
    public function index(IngredientsRepository $ingredientsRepository): Response
    {
        //todo : n'afficher que la liste des ingredient de l'utilisateur actuel. 
        return $this->render('user_ingredient/index.html.twig', [
            'ingredients' => $ingredientsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_ingredient_new", methods={"GET","POST"})
     */
    // todo : interdiction duplication du nom de l'ingredient (éviter les doublons dans la bdd)
    public function new(Request $request): Response
    {
        $ingredient = new Ingredients();
        $origin = $request->query->get('recette_id');
        $form = $this->createForm(IngredientsUserType::class, $ingredient);
        $form->handleRequest($request);
        $ingredient->setAuthorId($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            if($origin){
                return $this->redirectToRoute('recette_new_ingredients',[
                    'id' => $origin,
                ]);
            };
            return $this->redirectToRoute('user_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_ingredient_show", methods={"GET"})
     */
    public function show(Ingredients $ingredient): Response
    {
        return $this->render('user_ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_ingredient_edit", methods={"GET","POST"})
     */
    // todo : vérification de l'auteur de l'ingredient pour modification. 
    public function edit(Request $request, Ingredients $ingredient): Response
    {
        $form = $this->createForm(IngredientsUserType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    // /**
    //  * @Route("/{id}", name="user_ingredient_delete", methods={"POST"})
    //  */
    // public function delete(Request $request, Ingredients $ingredient): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($ingredient);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('user_ingredient_index', [], Response::HTTP_SEE_OTHER);
    // }
}
