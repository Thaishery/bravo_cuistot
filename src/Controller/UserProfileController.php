<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\User;
use App\Form\UserProfileType;
use App\Repository\NotesRepository;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;
use App\Service\AvatarFileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile")
 */
class UserProfileController extends AbstractController
{

    
    /**
     * @Route("/edit", name="profile_edit", methods={"GET","POST"})
     */
//fonction récupérée à partir de UserController
// On a enlevé les paramètres $user puisque seul le user connecté nous intéresse
// on a enlevé le paramètre pour le password
     public function edit(Request $request, AvatarFileUploader $avatarFileUploader, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        //on récupère l'user connecté
        $user = $this->getUser();
        
 // On n'utilise plus UserType mais UserProfileType parce que la modification du rôle et le mot de passe ne sera pas gérée dans le profil
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        
        
        if ($form->isSubmitted() && $form->isValid()) {
// on va traiter maintenant la partie avatar
// on récupère le fichier rentré dans le formulaire
// Si user rentre un mot de passe on fait le traitement du mdp        
        if ($form->get('plainPassword')->getData()!== null) {
// on donne un nouveau mdp en utilisant passwordEncoder
        $user->setPassword(
        $passwordEncoder->encodePassword(
        $user,
        $form->get('plainPassword')->getData()
        )
        );
    } 

$avatar = $form->get('avatar')->getData();
// si on a rentré un nouveau fichier pour l'avatar la fonction 

        
        if ($avatar) {
        $coverName = $avatarFileUploader->upload($avatar);
        $user->setAvatar($coverName);
        }

        else {

        $avatar = $user->getAvatar();
        $user->setAvatar($avatar);
        }
            $this->getDoctrine()->getManager()->flush();
// on a changé la redirection vers home
            return $this->redirectToRoute('home');
        }
// on utilise un autre template créé spécialement user/profile.html.twig
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="profile_show", methods={"GET","POST"})
     */
    public function show(Request $request,RecetteRepository $recetteRepository, UserRepository $userRepository, NotesRepository $notesRepository, $id){
        $user = $userRepository->findById($id);
        $listeRecette = $recetteRepository->findByUserId($id);
        $listeNotes = $notesRepository->findbyUserId($id);
        $moyenneActuelle = 0;
        $moyenneRecette[] = 0;

        if ($listeRecette != NULL) {
        for ($i = 0; $i<count($listeRecette); $i++) {
            $notesRecette = $listeRecette[$i]->getNotesId();
            $moyenneRecette[$i] = 0;
            if (count($notesRecette) !== 0) {
                for ($n = 0; $n<count($notesRecette); $n++) {
                    $moyenneRecette[$i] = $moyenneRecette[$i] + $notesRecette[$n]->getNote();
                    }
                $moyenneRecette[$i] = $moyenneRecette[$i]/count($notesRecette);
            }   

        }
        } 
        $compteurDeNotes = 0;  
        for ( $i = 0; $i<count($moyenneRecette); $i++) {
            if($moyenneRecette[$i] != 0){
                $compteurDeNotes = $compteurDeNotes +1;
            }
            $moyenneActuelle = $moyenneActuelle + $moyenneRecette[$i];
        }
        
        if ($compteurDeNotes != 0){
        $moyenneActuelle = $moyenneActuelle/$compteurDeNotes;
        }

        if($user){
        return $this->render('user/profile_show.html.twig', [
            'user' => $user,
            'listeRecette' => $listeRecette,
            'listeNotes' => $listeNotes,
            'moyenneActuelle'=> $moyenneActuelle,
            'moyenneRecette'=> $moyenneRecette,

        ]);
        }

        else{
            return $this->render('home/index.html.twig');
        }
    }

    // /**
    //  * @Route("/{id}", name="user_delete", methods={"POST"})
    //  */
    // public function delete(Request $request, User $user): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($user);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('user_index');
    // }
}
