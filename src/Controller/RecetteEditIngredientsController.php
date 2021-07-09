<?php

namespace App\Controller;

use App\Entity\IngredientsRecette;
use App\Form\IngredientsRecetteType;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteEditIngredientsController extends AbstractController
{
    /**
     * @Route("/recette/{idRecette}/edit/ingredients/{id}", name="recette_edit_ingredients")
     */
    // public function index(Request $request, IngredientsRecette $ingredientsRecette ): Response
    public function index(Request $request, IngredientsRecette $ingredientsRecette,int $idRecette, RecetteRepository $recetteRepository ): Response
    {
        $recette = $recetteRepository->findById($idRecette);
        $form = $this->createForm(IngredientsRecetteType::class, $ingredientsRecette);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recette_edit',[
                'id' => $idRecette,
            ]);
        }
        return $this->render('recette_edit_ingredients/index.html.twig', [
            'controller_name' => 'RecetteEditIngredientsController',
            'recette' => $recette,
            'ingredient' => $ingredientsRecette,
            'form' => $form->createView(),
        ]);
    }
}
