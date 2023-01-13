<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(User $user, Request $request, EntityManagerInterface $manager, 
    UserPasswordHasherInterface $hasher): Response
    {   // if the user is logged
        if(!$this->getUser()) {
            // if not logged, the user will be redirected to the login page
            return $this->redirectToRoute('security.login');
        }

        // if current user is different to the user that wants to change its profil (with the same id)
        if($this->getUser() !== $user) {
            //if not
            return $this->redirectToRoute('lieux.index');
        }

        //creating form
        $form = $this->createForm(UserType::class, $user);
        // submit
        $form->handleRequest($request);
        // if form is submit and valid
        if($form->isSubmitted() && $form->isValid()) {
            // if the password = password's current user
            if($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();
                //adding a succes message
                $this->addFlash(
                    'success',
                    'Les informations de votre compte ont bien été modifiées'
                );
            }else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe est incorrect.'
                );
            }
         
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/edition-mot-de-passe/{id}', name:'user.edit.password', methods:['GET', 'POST'])]
    public function editPassword(User $user, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager) : Response
    {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {
            $user->setPassword(
                $hasher->hashPassword(
                    $user,
                    $form->getData()['newPassword']
                )
            );

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le mot de passe a été modifié'
            );

            return $this->redirectToRoute('lieux.index');
        }else {
            $this->addFlash(
                'warning',
                'Le mot de passe est incorrect');
        }
    }
        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
