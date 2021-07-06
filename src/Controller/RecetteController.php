<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Etapes;
use App\Entity\IngredientsRecette;
use App\Entity\Notes;
use App\Entity\Recette;
use App\Entity\User;
use App\Form\CommentairesType;
use App\Form\EtapesType;
use App\Form\IngredientsRecetteType;
use App\Form\NotesType;
use App\Form\RecetteType;
use App\Repository\CommentairesRepository;
use App\Repository\EtapesRepository;
use App\Repository\IngredientsRecetteRepository;
use App\Repository\NotesRepository;
use App\Repository\RecetteRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\Length;

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
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        $recette->setAuthorId($user);
        

        if ($form->isSubmitted() && $form->isValid()) {
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
        // initialise le formulaire depuis le modéle IngredientsRecetteType.
        $form = $this->createForm(IngredientsRecetteType::class, $ingredients);
        $form->handleRequest($request);
        // ajoute l'id de la recette a la class IngredientsRecette. (via l'injection de dépendances)
        $ingredients->setRecetteId($recette);
        // on récupére la liste des ingrédients de la recette pour vérifier si elle est vide ou non
        $listeIngredient = $ingredientsRecetteRepository->findByRecetteId($id);

        //si la recette a modifier a un auteur différent de l'utilisateur actuel, ne pas permetre la modification de la liste d'ingrédients:
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
            //il n'y as pas encore d'ingrédients a la recette, on affiche donc le template initiale. 
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
    public function newEtapes(Request $request,int $id,EtapesRepository $etapesRepository, IngredientsRecetteRepository $ingredientsRecetteRepository, RecetteRepository $recetteRepository, Recette $recette): Response
    {
        //récupére l'utilisateur: 
        $user = $this->getUser();
        // initialise l'objet Etapes
        $etape = new Etapes();
        // initialise le formulaire depuis le modéle EtapesType.
        $form = $this->createForm(EtapesType::class, $etape);
        $form->handleRequest($request);
        // on récupére la liste des étapes de la recette pour vérifier si elle est vide ou non
        $listeEtapes = $etapesRepository->findByRecetteId($recette);
        // ajout de l'id de la recette a la class Etapes. (via l'injection de dépendances)
        $etape->setRecetteId($recette);
        // TODO : trouver un moyen d'incrémenter de 1 le numéro de l'étape automatiquement pour chaque nouvelle étape.
        // TODO : a faire dans les conditions qui suivent => si $listeEtapes != null et else. 
        

        //si la recette a modifier a un auteur différent de l'utilisateur actuel, ne pas permetre la modification de la liste d'étape:
        if ($recette->getAuthorId()->getId() !== $user->getId()){
            // forbiden.html.twig => a modifier (placeholder atm) les paramétres pouront être suprimer par la suite. 
            return $this->render('errors/forbiden.html.twig');
        }
        // si il y a deja des ingrédients de recette
        else if($listeEtapes != null){
            //todo : on utilise la fonction count sur notre tableau listeEtapes et on ajoute 1 a celui ci. 

            $etape->setIsNumber(count($listeEtapes)+1);
            //traitement formulaire 
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($etape);
                $entityManager->flush();
                
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
            // todo: listeEtape est vide, on peut donc passer is_number a 1 .
            $etape->setIsNumber(1);
            //traitement formulaire
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($etape);
                $entityManager->flush();
                return $this->redirectToRoute('recette_new_etapes',[
                    'id' => $id,
                ]);
            }
            //il n'y as pas encore d'étapes a la recette, on affiche donc le template initiale. 
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
        //on récupére la liste des Etapes trié par numéro d'etape. 
        $listeEtapes = $etapesRepository->findByRecetteIdOrderByIsNumber($id);
        //on récupére la listre des ingrédients de la recette. 
        $listeIngredient = $ingredientsRecetteRepository->findByRecetteId($id);
        // on compte les étape pour l'envoyer a la vue.
        $nomberEtapes = count($listeEtapes);
        // on récupére l'utilisateur pour vérification Id (recette toujour en création même si preview)
        $user = $this->getUser();
        //si la recette a modifier a un auteur différent de l'utilisateur actuel, ne pas permetre la modification de la liste d'étape:
            if ($recette->getAuthorId()->getId() !== $user->getId()){
                // forbiden.html.twig => a modifier (placeholder atm) les paramétres pouront être suprimer par la suite. 
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
        EtapesRepository $etapesRepository,
        IngredientsRecetteRepository $ingredientsRecetteRepository,
        CommentairesRepository $commentairesRepository,
        NotesRepository $notesRepository
    ): Response
    {
        //on récupére la liste des Etapes trié par numéro d'etape. 
        $listeEtapes = $etapesRepository->findByRecetteIdOrderByIsNumber($id);
        //on récupére la listre des ingrédients de la recette. 
        $listeIngredient = $ingredientsRecetteRepository->findByRecetteId($id);
        //on récupére la liste des commentaires .
        $listeCommentaires = $commentairesRepository->findByRecetteId($id);
        //on récupére l'utilisateur actuel : 
        $user = $this->getUser();
        // on compte les étape pour l'envoyer a la vue.
        $nomberEtapes = count($listeEtapes);
        // on gére le formulaire d'ajout de commentaire : 
        $commentaire = new Commentaires();
        $formCommentaire = $this->createForm(CommentairesType::class, $commentaire);
        $formCommentaire->handleRequest($request);
        $commentaire->setCreatedAt(new DateTimeImmutable());
        $commentaire->setUserId($user);
        $commentaire->setRecetteId($recette);

        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();
            
            return $this->redirectToRoute('recette_show',[
                'id' => $id,
            ]);
        }

        // on gére la notation : 
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
        // 2.1.1 on récupére l'utilisateur actuel : 
        // fait ligne 258
        
        // 2.1.2 on compare avec la liste des notes de la recette pour voir si l'id de l'utilisateur actuel y figure : 
        $haveNoted = false;
        for ($i = 0; $i < count($listeNotes); $i++){
            //il dois y avoir un probléme sur cette condition : 
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
            ]);
        }   
        // 2.1.3Bis sinon on initialise le formulaire d'ajout de note dans cette vue. 
        else{
            $note = new Notes();
            $formNote = $this->createForm(NotesType::class, $note);
            $formNote->handleRequest($request);
            $note->setRecetteId($recette);
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
        ]);
    }

    //a éditer pour que seul l'utilisateur qui a créer la recette puisse la modifier, ou que l'admin puisse (l'admin devra probablement utiliser /admin/administration)
    /**
     * @Route("/{id}/edit", name="recette_edit", methods={"GET","POST"})
     */
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
        // todo : vérifier lors de la création qu'il ne soit pas possible de passer diréctement a l'ajout d'étape sans avoir ajouter d'ingrédient


        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $formRecette->createView(),
            'listeIngredient' => $listeIngredient,
            'listeEtapes' => $listeEtapes,
        ]);
    }

    // a éditer aussi pour éviter la suppréssion des recette des autres. 
    /**
     * @Route("/{id}/delete", name="recette_delete", methods={"POST"})
     */
    public function delete(Request $request, Recette $recette): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recette_index');
    }
}
