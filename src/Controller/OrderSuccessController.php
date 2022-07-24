<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\CartService;
use App\Service\MailService as Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/ma-commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index(CartService $cart, string $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($order->getState() === 0) {

            $cart->remove();
            $order->setState(1);
            $this->entityManager->flush();

            $content = 'Bonjour ' . $order->getUser()->getFirstname() . ',<br/>Merci pour votre commande.<br/>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.';
            $mail = new Mail();
            $mail->send(
                $order->getUser()->getEmail(),
                $order->getUser()->getFirstname() . ' ' . $order->getUser()->getLastname(),
                'Votre commande sur La Boutique Française est bien validée !',
                $content
            );
        }

        return $this->render('order/success.html.twig', [
            'order' => $order
        ]);
    }
}
