<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Service\MailService as Mail;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($request->get('email')) {

            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if ($user) {

                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid('', true));
                $reset_password->setCreatedAt(new \DateTimeImmutable);
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                $url = $this->generateUrl('app_update_password', ['token' => $reset_password->getToken()]);
                $content = 'Bonjour ' . $user->getFirstname() . ',<br/>Vous avez demander à réinitialiser votre mot de passe sur La Boutique Française.<br/><br/>Merci de cliquer sur le lien suivant pour <a href="' . $url . '">réinitialiser votre mot de passe</a>';

                $mail = new Mail();
                $mail->send(
                    $user->getEmail(),
                    $user->getFirstname() . ' ' . $user->getLastname(),
                    'Nous avons bien reçu votre message',
                    $content
                );

                $this->addFlash('notice', 'Vous allez recevoir un lien de réinitialisation à l\'adresse ' . $user->getEmail() . '.');

            } else {

                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/mot-de-passe-oublie/{token}', name: 'app_update_password')]
    public function update(Request $request, UserPasswordHasherInterface $encoder, string $token): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {

            $this->addFlash('notice', 'Le token de sécurité n\'est pas valide. Merci de renouveler votre demande de réinitialisation du mot de passe.');
            $this->redirectToRoute('app_reset_password');
        }

        $now = new DateTime();
        $createdAt = $reset_password->getCreatedAt()->modify('+ 3 hour');

        if ($now > $createdAt) {

            $this->addFlash('notice', 'Votre demande de mot de passe a expirée. Mercid e la renouveler.');
            $this->redirectToRoute('app_reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $new_pwd = $form->get('new_password')->getData();
            $user = $reset_password->getUser();
            $password = $encoder->hashPassword($user, $new_pwd);
            $user->setPassword($password);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
