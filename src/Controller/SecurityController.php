<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
      /**
     * This controller allows us to log in
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/connexion', name: 'security.login', methods: ['GET', 'POST'])]
  
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

       /**
     * This controller allows to log out
     *
     * @return void
     */

    #[Route('/deconnexion', 'security.logout')]
 
    public function logout() 
    {
        // nothing to do here...
    }

     /**
     * This controller allows us to register a new user
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

    #[Route('/inscription', 'security.registration', methods: ['GET', 'POST'])]
   
    public function registration(Request $request, EntityManagerInterface $manager) : Response 
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        // if form is submitted and valid 
        if($form->isSubmitted() && $form->isValid()) {
        // then form will be send into the datas
            $user = $form->getData();
       
        //display a message flash to the user
            $this->addFlash(
                'success',
                'Votre compte a bien été crée.'
            );
        // manager will granted it
            $manager->persist($user);
            $manager->flush();
        // redirecting to login page   
            return $this->redirectToRoute('security.login');

        }

        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
