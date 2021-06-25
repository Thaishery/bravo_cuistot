<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientsType;
use App\Repository\IngredientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/administration/ingredients")
 */
class AdministrationIngredientsController extends AbstractController
{
    /**
     * @Route("/", name="administration_ingredients_index", methods={"GET"})
     */
    public function index(IngredientsRepository $ingredientsRepository): Response
    {
        return $this->render('administration_ingredients/index.html.twig', [
            'ingredients' => $ingredientsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="administration_ingredients_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ingredient = new Ingredients();
        $form = $this->createForm(IngredientsType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('administration_ingredients_index');
        }

        return $this->render('administration_ingredients/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_ingredients_show", methods={"GET"})
     */
    public function show(Ingredients $ingredient): Response
    {
        return $this->render('administration_ingredients/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="administration_ingredients_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ingredients $ingredient): Response
    {
        $form = $this->createForm(IngredientsType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_ingredients_index');
        }

        return $this->render('administration_ingredients/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_ingredients_delete", methods={"POST"})
     */
    public function delete(Request $request, Ingredients $ingredient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('administration_ingredients_index');
    }
}
