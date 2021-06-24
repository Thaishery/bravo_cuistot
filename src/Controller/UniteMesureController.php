<?php

namespace App\Controller;

use App\Entity\UniteMesure;
use App\Form\UniteMesureType;
use App\Repository\UniteMesureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/administration/unite_mesure")
 */
class UniteMesureController extends AbstractController
{
    /**
     * @Route("/", name="unite_mesure_index", methods={"GET"})
     */
    public function index(UniteMesureRepository $uniteMesureRepository): Response
    {
        return $this->render('unite_mesure/index.html.twig', [
            'unite_mesures' => $uniteMesureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="unite_mesure_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $uniteMesure = new UniteMesure();
        $form = $this->createForm(UniteMesureType::class, $uniteMesure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($uniteMesure);
            $entityManager->flush();

            return $this->redirectToRoute('unite_mesure_index');
        }

        return $this->render('unite_mesure/new.html.twig', [
            'unite_mesure' => $uniteMesure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="unite_mesure_show", methods={"GET"})
     */
    public function show(UniteMesure $uniteMesure): Response
    {
        return $this->render('unite_mesure/show.html.twig', [
            'unite_mesure' => $uniteMesure,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="unite_mesure_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UniteMesure $uniteMesure): Response
    {
        $form = $this->createForm(UniteMesureType::class, $uniteMesure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('unite_mesure_index');
        }

        return $this->render('unite_mesure/edit.html.twig', [
            'unite_mesure' => $uniteMesure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="unite_mesure_delete", methods={"POST"})
     */
    public function delete(Request $request, UniteMesure $uniteMesure): Response
    {
        if ($this->isCsrfTokenValid('delete'.$uniteMesure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($uniteMesure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('unite_mesure_index');
    }
}
