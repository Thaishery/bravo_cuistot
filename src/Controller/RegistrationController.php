<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\AvatarFileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//symfony 5.3 , doc : 
//https://symfony.com/blog/new-in-symfony-5-3-passwordhasher-component
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController {
    /**
     * @Route("/register", name="app_register")
     */
    public function register(AvatarFileUploader $avatarFileUploader,Request $request, UserPasswordHasherInterface $PasswordHasher): Response
    {
         $user = new User();
         $form = $this->createForm(RegistrationFormType::class, $user);
         $form->handleRequest($request);


         if ($form->isSubmitted() && $form->isValid()) {
             // encode the plain password
             $user->setPassword(
                 $PasswordHasher->hashPassword(
                     $user,
                     $form->get('plainPassword')->getData()
                 )
             );

         $avatar = $form->get('avatar')->getData();

        //  if ($avatar) {
        //       $coverName = $avatarFileUploader->upload($avatar);
        //       $user->setAvatar($coverName);
        //  }

        //   else {
        //       $user->setAvatar('placeholder.jpg');
        //   }

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();
             // do anything else you need here, like send an email

             return $this->redirectToRoute('home');
         }
 
         return $this->render('registration/register.html.twig', [
             'registrationForm' => $form->createView(),
         ]);
     }
 }
