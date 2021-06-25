<?php

namespace App\Controller;

use App\Entity\Alimentation;
use App\Form\AlimentationType;
use App\Repository\AlimentationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/administration/categories/alimentation")
 */
class AdministrationAlimentationController extends AbstractController
{
    /**
     * @Route("/", name="administration_alimentation_index", methods={"GET"})
     */
    public function index(AlimentationRepository $alimentationRepository): Response
    {
        return $this->render('administration_alimentation/index.html.twig', [
            'alimentations' => $alimentationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="administration_alimentation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $alimentation = new Alimentation();
        $form = $this->createForm(AlimentationType::class, $alimentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($alimentation);
            $entityManager->flush();

            return $this->redirectToRoute('administration_alimentation_index');
        }

        return $this->render('administration_alimentation/new.html.twig', [
            'alimentation' => $alimentation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_alimentation_show", methods={"GET"})
     */
    public function show(Alimentation $alimentation): Response
    {
        return $this->render('administration_alimentation/show.html.twig', [
            'alimentation' => $alimentation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="administration_alimentation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Alimentation $alimentation): Response
    {
        $form = $this->createForm(AlimentationType::class, $alimentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_alimentation_index');
        }

        return $this->render('administration_alimentation/edit.html.twig', [
            'alimentation' => $alimentation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_alimentation_delete", methods={"POST"})
     */
    public function delete(Request $request, Alimentation $alimentation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$alimentation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($alimentation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('administration_alimentation_index');
    }
}
