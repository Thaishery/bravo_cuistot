<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Etapes;
use App\Entity\IngredientsRecette;
use App\Entity\Notes;
use App\Entity\Recette;
use App\Form\CommentairesType;
use App\Form\EtapesType;
use App\Form\FavoriType;
use App\Form\RemoveFavoriType;
use App\Form\IngredientsRecetteType;
use App\Form\NotesType;
use App\Form\RecetteType;
use App\Repository\CommentairesRepository;
use App\Repository\EtapesRepository;
use App\Repository\IngredientsRecetteRepository;
use App\Repository\NotesRepository;
use App\Repository\RecetteRepository;
use App\Service\EtapesFileUploader;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RecettesFileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/recette")
 */
class RecetteController extends AbstractController
{
    /**
     * @Route("/", name="recette_index", methods={"GET"})
     */
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    //être connecter pour créer une recette! 
    /**
     * @IsGranted("ROLE_USER", message="Veuillez vous connecter ou créer un compte pour pouvoir créer une recette!")
     * @Route("/new", name="recette_new", methods={"GET","POST"})
     */
    public function new(Request $request, RecettesFileUploader $recetteFileUploader): Response
    {
        $user = $this->getUser();
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        $recette->setAuthorId($user);
        

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupére l'image de recette si il y en a une : 
            $imageRecette= $form->get('image')->getData();
            if($imageRecette){
                $imageName = $recetteFileUploader->upload($imageRecette);
                $recette->setImage($imageName);
            }
            else{
                $recette->setImage('placeholder.jpg');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('recette_new_ingredients',[
                'id' => $recette->getId(),
            ]);
        }

        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
        ]);
    }


    //ajout des ingrédients : 
    /**
     * @IsGranted("ROLE_USER", message="Veuillez vous connecter ou créer un compte pour pouvoir créer une recette!")
     * @Route("/new/{id}/ingredient", name="recette_new_ingredients", methods={"GET","POST"})
     */
    public function newIngredients(Request $request, $id, IngredientsRecetteRepository $ingredientsRecetteRepository, RecetteRepository $recetteRepository, Recette $recette): Response
    {
        //récupére l'utilisateur: 
        $user = $this->getUser();
        // initialise l'objet Ingrédients
        $ingredients = new IngredientsRecette();
        // initialise le formulaire depuis le modèle IngredientsRecetteType.
        $form = $this->createForm(IngredientsRecetteType::class, $ingredients);
        $form->handleRequest($request);
        // ajoute l'id de la recette à la class IngredientsRecette. (via l'injection de dépendances)
        $ingredients->setRecetteId($recette);
        // on récupére la liste des ingrédients de la recette pour vérifier si elle est vide ou non
        $listeIngredient = $ingredientsRecetteRepository->findByRecetteId($id);

        //si la recette à modifier a un auteur différent de l'utilisateur actuel, ne pas permetre la modification de la liste d'ingrédients:
        if ($recette->getAuthorId()->getId() !== $user->getId()){
            // forbiden.html.twig => a modifier (placeholder atm)
            return $this->render('errors/forbiden.html.twig');
        }
        // si il y a deja des ingrédients de recette
        else if($listeIngredient != null){
            //traitement formulaire 
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ingredients);
                $entityManager->flush();
                
                return $this->redirectToRoute('recette_new_ingredients',[
                    'id' => $id,
                ]);
            }
            //on affiche le deuxieme template avec le bouton étape suivante.
            return $this->render('recette/new_ingredients_suite.html.twig', [
                'id'=> $id,
                'listeIngredients' => $listeIngredient,
                'ingredients' => $ingredients,
                'form' => $form->createView(),
            ]);
        }
        else {
            //traitement formulaire
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ingredients);
                $entityManager->flush();
                return $this->redirectToRoute('recette_new_ingredients',[
                    'id' => $id,
                ]);
            }
            //il n'y a pas encore d'ingrédients à la recette, on affiche donc le template initial. 
            return $this->render('recette/new_ingredients.html.twig', [
                'id'=> $id,
                'ingredients' => $ingredients,
                'form' => $form->createView(),
            ]);
        }
    }


    //ajout des étapes : 
    /**
     * @IsGranted("ROLE_USER", message="Veuillez vous connecter ou créer un compte pour pouvoir créer une recette!")
     * @Route("/new/{id}/etapes", name="recette_new_etapes", methods={"GET","POST"})
     */
    public function newEtapes(Request $request,int $id, EtapesFileUploader $etapesFileUploader ,EtapesRepository $etapesRepository, IngredientsRecetteRepository $ingredientsRecetteRepository, RecetteRepository $recetteRepository, Recette $recette): Response
    {
        //on récupére la liste des ingrédients : 
        $listeIngredient = $ingredientsRecetteRepository->findByRecetteId($id);
        //si la liste d'ingrédient est vide, on redirige vers l'ajout d'ingrédient. Une recette a forcement des ingrédients avant d'avoir des étapes. 
        if($listeIngredient == null){
            return $this->redirectToRoute('recette_new_ingredients',[
                'id'=>$id
            ]);
        }
        //récupére l'utilisateur: 
        $user = $this->getUser();
        // initialise l'objet Etapes
        $etape = new Etapes();
        // initialise le formulaire depuis le modèle EtapesType.
        $form = $this->createForm(EtapesType::class, $etape);
        $form->handleRequest($request);
        // on récupére la liste des étapes de la recette pour vérifier si elle est vide ou non
        $listeEtapes = $etapesRepository->findByRecetteId($recette);
        // ajout de l'id de la recette à la class Etapes. (via l'injection de dépendances)
        $etape->setRecetteId($recette);

        //si la recette à modifier a un auteur différent de l'utilisateur actuel, ne pas permetre la modification de la liste d'étape:
        if ($recette->getAuthorId()->getId() !== $user->getId()){
            // forbiden.html.twig => a modifier (placeholder atm) les paramétres pouront être suprimés par la suite. 
            return $this->render('errors/forbiden.html.twig');
        }
        // si il y a deja des étapes de recette
        else if($listeEtapes != null){
            //on définie le numéro de l'etape, pour cela : 
            //on utilise la fonction count() sur notre tableau et ajout 1 
            $etape->setIsNumber(count($listeEtapes)+1);
            //traitement formulaire 
            if ($form->isSubmitted() && $form->isValid()) {
                //on récupére les données du champ image (null si il n'y en a pas.)
                $etapeImage = $form->get('image')->getData();
                //si !null on traite l'image
                if($etapeImage){
                    $imageName = $etapesFileUploader->upload($etapeImage);
                    $etape->setImage($imageName);
                }
                //sinon on met un placeholder : 
                else{
                    $etape->setImage('placeholder.jpg');
                }
                //on envoie le tout en bdd
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($etape);
                $entityManager->flush();
                //on redirige sur le même formulaire pour pouvoir ajouter des étapes supplémentaires si besoin : 
                return $this->redirectToRoute('recette_new_etapes',[
                    'id' => $id,
                ]);
            }
            //on affiche le deuxieme template avec le bouton étape suivante.
            return $this->render('recette/new_etapes_suite.html.twig', [
                'listeEtapes' => $listeEtapes,
                'etape' => $etape,
                'form' => $form->createView(),
            ]);
        }
        //listeEtape est null => il n'y a donc pas encore d'étape! 
        else {
            //listeEtape est vide, on peut donc passer is_number à 1 .
            $etape->setIsNumber(1);
            //traitement formulaire
            if ($form->isSubmitted() && $form->isValid()) {
                //on récupére les donées du champ image (null si il n'y en a pas.)
                $etapeImage = $form->get('image')->getData();
                //si !null on traite l'image
                if($etapeImage){
                    $imageName = $etapesFileUploader->upload($etapeImage);
                    $etape->setImage($imageName);
                }
                //sinon on met un placeholder : 
                else{
                    $etape->setImage('placeholder.jpg');
                }
                //on envoie le tout en bdd
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($etape);
                $entityManager->flush();
                return $this->redirectToRoute('recette_new_etapes',[
                    'id' => $id,
                ]);
            }
            //il n'y a pas encore d'étapes à la recette, on affiche donc le template initial. 
            return $this->render('recette/new_etapes.html.twig', [
                'etape' => $etape,
                'form' => $form->createView(),
            ]);
        }
    }
    //preview recette : 
    /**
     * @IsGranted("ROLE_USER", message="Veuillez vous connecter ou créer un compte pour pouvoir créer une recette!")
     * @Route("/new/{id}/preview", name="recette_new_preview", methods={"GET","POST"})
     */
    public function preview(Recette $recette,int $id, EtapesRepository $etapesRepository, IngredientsRecetteRepository $ingredientsRecetteRepository): Response{
        //on récupére la liste des Etapes triées par numéro d'étape. 
        $listeEtapes = $etapesRepository->findByRecetteIdOrderByIsNumber($id);
        //on récupère la liste des ingrédients de la recette. 
        $listeIngredient = $ingredientsRecetteRepository->findByRecetteId($id);
        // on compte les étapes pour l'envoyer à la vue.
        $nomberEtapes = count($listeEtapes);
        // on récupére l'utilisateur pour vérification Id (recette toujour en création même si preview)
        $user = $this->getUser();
        //si la recette à modifier a un auteur différent de l'utilisateur actuel, ne pas permetre la modification de la liste d'étape:
            if ($recette->getAuthorId()->getId() !== $user->getId()){
                // forbiden.html.twig => a modifier (placeholder atm) les paramètres pouront être suprimés par la suite. 
                return $this->render('errors/forbiden.html.twig');
            }
        
        return $this->render('recette/preview.html.twig',[
            'recette' => $recette,
            'nombreEtapes'=>$nomberEtapes,
            'listeIngredient' => $listeIngredient,
            'listeEtapes' => $listeEtapes,
        ]);
    }

    //on affiche la recette
    /**
     * @Route("/{id}", name="recette_show", methods={"GET","POST"})
     */
    public function show(
        Request $request,
        Recette $recette,
        int $id,
        RecetteRepository $recetteRepository,
        EtapesRepository $etapesRepository,
        IngredientsRecetteRepository $ingredientsRecetteRepository,
        CommentairesRepository $commentairesRepository,
        NotesRepository $notesRepository,
        RemoveFavoriType $removeFavori
    ): Response
    {
        //on récupére la liste des Etapes triées par numéro d'étape. 
        $listeEtapes = $etapesRepository->findByRecetteIdOrderByIsNumber($id);
        //on récupére la liste des ingrédients de la recette. 
        $listeIngredient = $ingredientsRecetteRepository->findByRecetteId($id);
        //on récupére la liste des commentaires .
        $listeCommentaires = $commentairesRepository->findByRecetteId($id);
        //on récupére l'utilisateur actuel : 
        $user = $this->getUser();
        // on compte les étape pour l'envoyer a la vue.
        $nomberEtapes = count($listeEtapes);
        // on gére le formulaire d'ajout de commentaire :
            //on crée notre objet Commentaires :  
        $commentaire = new Commentaires();
        //on initialise le formulaire : 
        $formCommentaire = $this->createForm(CommentairesType::class, $commentaire);
        $formCommentaire->handleRequest($request);
        //on définie la date de creation du commentaire via un objet DateTimeImmutable.
        $commentaire->setCreatedAt(new DateTimeImmutable());
        //on définie l'utilisateur qui a posté le commentaire
        $commentaire->setUserId($user);
        //on définie la recette a laquelle le commentaire est attribué : 
        $commentaire->setRecetteId($recette);

        
        
        //si on choisit d'envoyer un commentaire (on clique sur save)
        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();
            
            return $this->redirectToRoute('recette_show',[
                'id' => $id,
            ]);
        }

        //on "contruit le bouton "ajouter en favori" à partir de FavoriType.php
        $formFavori=$this->createForm(FavoriType::class,$user);
        $formFavori->handleRequest($request);
        //on récupère la recette affichée
        $recetteChoisie = $recetteRepository->find($id);
        
        //si on clique sur "ajouter en favori" :
        if ($formFavori->isSubmitted() && $formFavori->isValid()) {
            //on utilise les fonction addRecettesFavId() de l'entity User et addUsersFavId() de l'entity Recette pour renseigner la table recette-user
            $user->addRecettesFavId($recetteChoisie);
            $recetteChoisie->addUsersFavId($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            
            return $this->redirectToRoute('recette_show',[
                'id' => $id,
            ]);
        }
            //on "contruit le bouton "retirer des favoris" à partir de RemoveFavoriType.php
            $removeFavori=$this->createForm(RemoveFavoriType::class,$user);
            $removeFavori->handleRequest($request);
            //on récupère la recette affichée
            $recetteChoisie = $recetteRepository->find($id);

            //si on clique sur "retirer des favoris" à partir de RemoveFavoriType.php
        if ($removeFavori->isSubmitted() && $removeFavori->isValid()) {
            //on utilise les fonction addRecettesFavId() de l'entity User et addUsersFavId() de l'entity Recette pour renseigner la table recette-user
            $user->RemoveRecettesFavId($recetteChoisie);
            $recetteChoisie->RemoveUsersFavId($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        
            return $this->redirectToRoute('recette_show',[
                'id' => $id,
            ]);
        }        
        

        // on gère la notation : 
        // 1. on gére les notes deja présente : 
        // 1.1 on récupére la liste des notes de la recettes : 
        $listeNotes = $notesRepository->findByRecetteId($id);
        // 1.2 on fait la moyenne : 
        $totalNotes = 0;
        for ($i = 0 ; $i < count($listeNotes); $i ++){
            $totalNotes = $totalNotes + $listeNotes[$i]->getNote();
        }
        // 1.3 rien ne nous assure qu'il y ai des notes, il faut donc ajouter une condition pour éviter de diviser par 0: 
        if($totalNotes != 0 ){
        $moyenneNote = $totalNotes/count($listeNotes);
        }
        else{
            $moyenneNote = 0;
        }
        // 2. on laisse l'utilisateur actuel noter la recette : 
        // 2.1 on vérifie si l'utilisateur actuel a deja noter la recette : 
        // 2.1.1 on récupére l'utilisateur actuel : fait ligne 279
        
        // 2.1.2 on compare avec la liste des notes de la recette pour voir si l'id de l'utilisateur actuel y figure : 
        $haveNoted = false;
        for ($i = 0; $i < count($listeNotes); $i++){
            if($listeNotes[$i]->getUserId() == $this->getUser()){
                $haveNoted = true;
            }
        }
        // 2.1.3 si l'utilisateur a deja noter la recette, on récupére la note et renvoie la vue, 
        
        if($haveNoted == true){
            $noteUser = $notesRepository->findByUserId($id);
            return $this->render('recette/show.html.twig', [
                'recette' => $recette,
                'nombreEtapes'=>$nomberEtapes,
                'listeIngredient' => $listeIngredient,
                'listeEtapes' => $listeEtapes,
                'listeCommentaires' => $listeCommentaires,
                'moyenneNote' => $moyenneNote,
                'noteUser' => $noteUser,
                'haveNoted' =>$haveNoted,
                'formCommentaire' => $formCommentaire->createView(),
                'formFavori' => $formFavori->createView(),
                'removeFavori' => $removeFavori->createView(),
                'user' => $user,

            ]);
        }   
        // 2.1.3Bis sinon on initialise le formulaire d'ajout de note dans cette vue. 
        else{
            //on crée notre objet Notes
            $note = new Notes();
            //on crée notre formulaire
            $formNote = $this->createForm(NotesType::class, $note);
            $formNote->handleRequest($request);
            //on définie la recette associé a la note
            $note->setRecetteId($recette);
            //on définie l'utilisateur associé a la note
            $note->setUserId($user);
            if ($formNote->isSubmitted() && $formNote->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($note);
                $entityManager->flush();
                
                return $this->redirectToRoute('recette_show',[
                    'id' => $id,
                ]);
            }
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'nombreEtapes'=>$nomberEtapes,
            'listeIngredient' => $listeIngredient,
            'listeEtapes' => $listeEtapes,
            'listeCommentaires' => $listeCommentaires,
            'moyenneNote' => $moyenneNote,
            'haveNoted' =>$haveNoted,
            'formNote' => $formNote->createView(),
            'formCommentaire' => $formCommentaire->createView(),
            'formFavori' => $formFavori->createView(),
            'removeFavori' => $removeFavori->createView(),
            'user' => $user,

        ]);
    }

    //a éditer pour que seul l'utilisateur qui a créer la recette puisse la modifier, ou que l'admin puisse (l'admin devra probablement utiliser /admin/administration)
    /**
     * @Route("/{id}/edit", name="recette_edit", methods={"GET","POST"})
     */
    //todo : gestion image recette + dans RecetteEditEtapes => gestion image etape.
    public function edit(
        Request $request,
        Recette $recette,
        EtapesRepository $etapesRepository,
        IngredientsRecetteRepository $ingredientsRecetteRepository,
        int $id
        ): Response
    {
        //on récupére l'utilisateur,
        $user = $this->getUser();
        //on récupére les roles,
        $roles = $user->getRoles();
        //on crée un bool "Admin" qui vaux vrais si "ROLE_ADMIN" est dans la variable $roles, 
        // https://stackoverflow.com/questions/14585897/how-to-check-the-user-role-inside-form-builder-in-symfony2 (dernier message)
        $boolAdmin = in_array('ROLE_ADMIN', $roles);
        //si l'utilisateur n'est pas administrateur ou l'autheur de la recette : 
        // on renvoie vers erros/fobiden.html.twig.
        if ($boolAdmin == false){
            if ($recette->getAuthorId()->getId() !== $user->getId()){
                return $this-> render('errors/forbiden.html.twig');
            }
        }
        //récupération liste ingrédients et etapes : 
        $listeIngredient = $ingredientsRecetteRepository->findByRecetteId($id);
        $listeEtapes = $etapesRepository->findByRecetteId($id);
        //on génére les formulaires : 
        //la recette: 
        $formRecette = $this->createForm(RecetteType::class, $recette);
        $formRecette->handleRequest($request);

        if ($formRecette->isSubmitted() && $formRecette->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recette_index');
        }
        //les ingrédient : 
        // on vérifie si la liste des ingrédient comprend bien au moins 1 ingrédient, sinon on redirige vers une page d'érreur disant que la recette que 
        // l'utilisateur essaie de modifier n'est pas fini avec un lien de redirection vers l'ajout d'un 1er ingrédient. 

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $formRecette->createView(),
            'listeIngredient' => $listeIngredient,
            'listeEtapes' => $listeEtapes,
        ]);
    }

    // todo : a éditer aussi pour éviter la suppréssion des recette des autres. 
    /**
     * @Route("/{id}/delete", name="recette_delete", methods={"POST"})
     */
    public function delete(Request $request, Recette $recette): Response
    {
        //on récupére l'utilisateur et son role : 
        $user = $this->getUser();
        $roles = $user->getRoles();
        $boolAdmin = in_array('ROLE_ADMIN', $roles);
        if ($boolAdmin == false){
            if ($recette->getAuthorId()->getId() !== $user->getId()){
                return $this-> render('errors/forbiden.html.twig');
            }
        };

        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recette_index');
    }
}
