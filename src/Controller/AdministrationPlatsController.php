<?php

namespace App\Controller;

use App\Entity\Plats;
use App\Form\PlatsType;
use App\Repository\PlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/administration/categories/plats")
 */
class AdministrationPlatsController extends AbstractController
{
    /**
     * @Route("/", name="administration_plats_index", methods={"GET"})
     */
    public function index(PlatsRepository $platsRepository): Response
    {
        return $this->render('administration_plats/index.html.twig', [
            'plats' => $platsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="administration_plats_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $plat = new Plats();
        $form = $this->createForm(PlatsType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($plat);
            $entityManager->flush();

            return $this->redirectToRoute('administration_plats_index');
        }

        return $this->render('administration_plats/new.html.twig', [
            'plat' => $plat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_plats_show", methods={"GET"})
     */
    public function show(Plats $plat): Response
    {
        return $this->render('administration_plats/show.html.twig', [
            'plat' => $plat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="administration_plats_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Plats $plat): Response
    {
        $form = $this->createForm(PlatsType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_plats_index');
        }

        return $this->render('administration_plats/edit.html.twig', [
            'plat' => $plat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_plats_delete", methods={"POST"})
     */
    public function delete(Request $request, Plats $plat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($plat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('administration_plats_index');
    }
}
