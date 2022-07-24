<?php

namespace App\Controller;

use App\Service\MailService as Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegisterType;
use App\Entity\User;

class RegisterController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;
        $notify_state = 'success';
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email) {

                $password = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $notification = 'Votre inscription s\'est bien déroulée. Vous pouvez dès à présent vous connecter à votre compte.';
                $content = 'Bonjour ' . $user->getFirstname() . ',<br/>Bienvenue sur la première boutique dédiée au made in France.<br/><br/>Donec rutrum congue leo eget malesuada. Nulla quis lorem ut libero malesuada feugiat.';
                $mail = new Mail();
                $mail->send(
                    $user->getEmail(),
                    $user->getFirstname() . ' ' . $user->getLastname(),
                    'Bienvenue sur La Boutique Française',
                    $content
                );

            } else {

                $notification = 'L\'email que vous avez renseigné existe déjà.';
                $notify_state = 'danger';
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'notify_state' => $notify_state
        ]);
    }
}
