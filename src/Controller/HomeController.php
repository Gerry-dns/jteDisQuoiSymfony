<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods:['GET'])]
    public function index(LieuRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
            $lieux = $paginator->paginate(
            // display 'lieux' that are assigned to the current user
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
            return $this->render('pages/home/index.html.twig', [
                'controller_name' => 'LieuxController',
                'lieux' => $lieux,
            ]);
    }
    
}
