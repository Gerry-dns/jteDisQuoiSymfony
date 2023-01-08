<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuFormType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LieuController extends AbstractController
{
    /**
     * This function diplays all 'Lieux'
     *
     * @param LieuRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/lieu', name: 'app_lieu', methods:['GET'])]
    // injection de dépendance -> injecter un service dans les paramètres de la fonction du controller
    public function index(LieuRepository $repository, PaginatorInterface $paginator, Request $request): Response
    { 
        $lieux = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/lieu/index.html.twig', [
            'lieux' => $lieux,
            'controller_name' => 'LieuController',
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
    /* we call the EntityManagerInterface, it will help to get into the database */
    public function new(Request $request, EntityManagerInterface $manager) : Response {
        $lieu = new Lieu();
        $form = $this->createForm(LieuFormType::class, $lieu);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $lieu = $form->getData();
            /* manager has to add ('persist' and 'flush') the data into the database */
            /* Persist is kind of "commit", flush is a "push" */
            $manager->persist($lieu);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre lieu a bien été enregistré. Merci pour votre aide !'
            );

            /* To redirect to the page 'lieu' */

            return $this->redirectToRoute('app_lieu');
        }

        return $this->render('pages/lieu/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
   
    /* CRUD UPDATE */
    #[Route('/lieu/edition/{id}', 'lieu.edit', methods: ['GET', 'POST'])]
    public function edit(
        Lieu $lieu, 
        Request $request, 
        EntityManagerInterface $manager
    ): Response {
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
            return $this->redirectToRoute('app_lieu');
        }
     
        $form = $this->createForm(LieuFormType::class, $lieu);

        return $this->render('pages/lieu/edit.html.twig', [

            'form' => $form->createView()
        ]);
    }
    #[Route('/lieu/suppression/{id}', 'lieu.delete', methods : ['POST'])]
    public function delete(EntityManagerInterface $manager, Lieu $lieu) : Response 
    {
        if(!$lieu) {
            $this->addFlash(
                'succes',
                'Le lieu en question n\'a pas été trouvé !'
            );
            return $this->redirectToRoute('app_lieu');
        }
        $manager->remove($lieu);
        $manager->flush();

        $this->addFlash(
            'succes',
            'Votre lieu a été supprimé avec succès !'
        );

        return $this->redirectToRoute('app_lieu');

    }
}

