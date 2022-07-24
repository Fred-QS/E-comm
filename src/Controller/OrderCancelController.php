<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\MailService as Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/ma-commande/erreur/{stripeSessionId}', name: 'app_order_cancel')]
    public function index(string $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $content = 'Bonjour ' . $order->getUser()->getFirstname() . ',<br/>Une erreur s\'est produite avec votre commande...<br/>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.';
        $mail = new Mail();
        $mail->send(
            $order->getUser()->getEmail(),
            $order->getUser()->getFirstname() . ' ' . $order->getUser()->getLastname(),
            'Votre commande sur La Boutique Française n\a pas pu aboutir.',
            $content
        );

        return $this->render('order/cancel.html.twig', [
            'order' => $order
        ]);
    }
}
