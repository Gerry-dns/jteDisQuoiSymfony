<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Repository\LieuRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LieuxController extends AbstractController
{
    #[Route('/lieux', name: 'lieux.index', methods:['GET'])]
    public function displayAllLieux(LieuRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {    
        $lieux = $paginator->paginate(
        // display 'lieux' that are assigned to the current user
        $repository->findAll(),
        $request->query->getInt('page', 1), /*page number*/
        10 /*limit per page*/
    );
        return $this->render('pages/lieu/lieux.html.twig', [
            'controller_name' => 'LieuxController',
            'lieux' => $lieux,
        ]);
    }

    #[Route('/lieux/{id}', name: 'lieux.show', methods:['GET'])]

    public function show(LieuRepository $repository, Lieu $lieu): Response
    {    
        return $this->render('pages/lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

}
