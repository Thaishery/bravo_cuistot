<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InterfaceAdministrationController extends AbstractController
{
    /**
     * @Route("/interface/administration", name="interface_administration")
     */
    public function index(): Response
    {
        return $this->render('interface_administration/index.html.twig', [
            'controller_name' => 'InterfaceAdministrationController',
        ]);
    }
}
