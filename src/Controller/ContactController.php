<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index')]
    public function index(EntityManagerInterface $manager, Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        // if there is a current user
        if($this->getUser()) {
            // FullName and Email will be already filled-in
            $contact->setFullName($this->getUser()->getFullName())
                ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            // Email

            $email = (new Email())
            ->from($contact->getEmail())
            ->to('admin@jtedisquoi.com')
            ->subject($contact->getSubject())
            ->html($contact->getMessage());

        $mailer->send($email);


            $this->addFlash(
                'success',
                'Votre message a bien été envoyé'
            );
            
            return $this->redirectToRoute('contact.index');
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }
}
