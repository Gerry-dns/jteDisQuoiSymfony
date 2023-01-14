<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Mark;
use App\Form\MarkType;
use App\Repository\LieuRepository;
use App\Repository\MarkRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        4 /*limit per page*/
    );
        return $this->render('pages/lieu/lieux.html.twig', [
            'controller_name' => 'LieuxController',
            'lieux' => $lieux,
        ]);
    }

    #[Route('/lieux/{id}', name: 'lieux.show', methods:['GET', 'POST'])]

    public function show(Lieu $lieu, Request $request, 
    MarkRepository $markRepository, 
    EntityManagerInterface $manager) : Response
    {   
        $mark = new Mark();
        $form = $this->createForm(MarkType::class, $mark);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // current user will give a mark
            $mark->setUser($this->getUser())
                ->setLieu($lieu);

            // has the user already given a mark? 
            $existingMark = $markRepository->findOneBy([
                'user' => $this->getUser(),
                'lieu' => $lieu
            ]);
            // if existringMark doesn't exist
        if(!$existingMark) {
            // then manager persist and flush $mark 
            $manager->persist($mark);
        } else {
            $this->addFlash(
                'fail',
                'Vous ne pouvez pas noter plusieurs fois'
            );
            
        }
            $manager->flush();
            // display a succes message
            $this->addFlash(
                'success',
                'Votre notre à a bien été prise en compte'
            );
            // redirect to lieu.show according the its ID
            return $this->redirectToRoute('lieux.show', ['id' => $lieu->getId()]);

        }
        return $this->render('pages/lieu/show.html.twig', [
            'lieu' => $lieu,
            'form' => $form->createView()
        ]);
    }

}
