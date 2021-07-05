<?php

namespace App\Controller;

use App\Entity\Etapes;
use App\Form\EtapesType;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteEditEtapesController extends AbstractController
{
    /**
     * @Route("/recette/{idRecette}/edit/etapes/{id}", name="recette_edit_etapes")
     */
    // public function index(Request $request, IngredientsRecette $ingredientsRecette ): Response
    public function index(Request $request, Etapes $etapes,int $idRecette, RecetteRepository $recetteRepository ): Response
    {
        $recette = $recetteRepository->findById($idRecette);
        $form = $this->createForm(EtapesType::class, $etapes);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recette_edit',[
                'id' => $idRecette,
            ]);
        }
        return $this->render('recette_edit_etapes/index.html.twig', [

            'recette' => $recette,
            'etapes' => $etapes,
            'form' => $form->createView(),
        ]);
    }
}
