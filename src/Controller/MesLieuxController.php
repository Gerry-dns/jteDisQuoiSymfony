<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuFormType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MesLieuxController extends AbstractController
{
    /**
     * This function diplays 'Lieux' the current user uploaded
     *
     * @param LieuRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/mesLieux', name: 'mesLieux.index', methods:['GET'])]
    #[IsGranted('ROLE_USER')]
     // injection de dépendance -> injecter un service dans les paramètres de la fonction du controller
     public function findByUser(LieuRepository $repository, PaginatorInterface $paginator, Request $request): Response
     { 
         $lieux = $paginator->paginate(
             // display 'lieux' that are assigned to the current user
             $repository->findBy(['user' => $this->getUser()]),
             $request->query->getInt('page', 1), /*page number*/
             10 /*limit per page*/
         );
 
         return $this->render('pages/lieu/mesLieux.html.twig', [
             'lieux' => $lieux,
         ]);
     }
    
    /**
     * FORM for the CRUD CREATE a new place
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
   
    /* We create a Route we write it in French and the methods are GET and POST (POST to create)*/
    #[Route('/lieu/nouveau', 'lieu.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    /* we call the EntityManagerInterface, it will help to get into the database */
    public function new(Request $request, EntityManagerInterface $manager) : Response {
        $lieu = new Lieu();
        $form = $this->createForm(LieuFormType::class, $lieu);
        // get the request
        $form->handleRequest($request);
        // is form is submitted and valid
        if($form->isSubmitted() && $form->isValid()) {
            $lieu = $form->getData();
            /* when a 'lieu' is created, it will be assigned to the current user */
            $lieu->setUser($this->getUser());
            /* manager has to add ('persist' and 'flush') the data into the database */
            /* Persist is kind of "commit", flush is a "push" */
            $manager->persist($lieu);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre lieu a bien été enregistré. Merci pour votre aide !'
            );

            /* To redirect to the page 'lieu' */

            return $this->redirectToRoute('mesLieux.index');
        }

        return $this->render('pages/lieu/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
   
    /* CRUD UPDATE */
     // is granted only USER and current user is the same as the one who want create a new lieu
    #[Security("is_granted('ROLE_USER') and user === lieu.getUser()")]
    #[Route('/lieu/edition/{id}', 'lieu.edit', methods: ['GET', 'POST'])]
    public function edit(
        Lieu $lieu, 
        Request $request, 
        EntityManagerInterface $manager
    ): Response 
    {
        
        $form = $this->createForm(LieuFormType::class, $lieu);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $lieu = $form->getData();

            $manager->persist($lieu);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre lieu a bien été modifié. Merci pour votre aide !'
            );

            return $this->redirectToRoute('mesLieux.index');
        }
     
        $form = $this->createForm(LieuFormType::class, $lieu);

        return $this->render('pages/lieu/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    // CRUD DELETE
    #[Route('/lieu/suppression/{id}', 'lieu.delete', methods : ['GET'])]
    public function delete(EntityManagerInterface $manager, Lieu $lieu) : Response 
    {
        $manager->remove($lieu);
        $manager->flush();

        $this->addFlash(
            'succes',
            'Votre lieu a été supprimé avec succès !'
        );
        return $this->redirectToRoute('mesLieux.index');

    }
}

