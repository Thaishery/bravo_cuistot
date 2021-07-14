<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentairesController extends AbstractController
{
    /**
     * @Route("/commentaires/edit/{id}", name="commentaires_edit")
     */
    public function index(Request $request,Commentaires $commentaires ): Response
    {
        $form = $this->createForm(CommentairesType::class, $commentaires);
        $form->handleRequest($request);
        $recette_id =(string)$commentaires->getRecetteId()->getId();
        $commentaires->setEditedAt(new DateTimeImmutable());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->get('content')->getData();

           //si il y a un commentaire, et qu'il est différent de celui deja présent, on envoie en bdd
           if($commentaire){
               $commentaires->setContent($commentaire);
               $this->getDoctrine()->getManager()->flush();
               return $this->redirectToRoute('commentaires_edit',[
                   'id'=>(string)$commentaires->getId(),
                   'recette_id'=>(string)$recette_id,
               ]);
           }
           //si il est identique, on redirige juste sans envoie en bdd
           else{
               return $this->redirectToRoute('commentaires_edit',[
                'id'=>(string)$commentaires->getId(),
            ]);
           }
        }
        return $this->render('commentaires/edit.html.twig', [
            'form' => $form->createView(),
            'id' => (string)$recette_id,
        ]);
    }
}
