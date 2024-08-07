<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * Controller that sends information from contact form
     *
     * @param Contact $contact
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/contact', name: 'app_contact', methods:['GET', 'POST'])]
    public function index(
        EntityManagerInterface $manager,
        MailService $mailService,
        Request $request
        ): Response
    {
        $contact = new Contact();

        if ($this->getUser()) {
            $contact->setFullName($this->getUser()->getFullName())
                ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $contact = $form->getData();

            $mailService->sendEmail(
                $contact->getEmail(),
                $contact->getSubject(),
                $contact->getMessage()
            );

            $manager->persist($contact);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your message has been sent!',
            );

            return $this->redirectToRoute('app_contact');
        }
        return $this->render('pages/contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
