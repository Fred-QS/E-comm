<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-compte/mes-commandes', name: 'app_account_orders')]
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());

        return $this->render('account/orders.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/mon-compte/ma-commande/{reference}', name: 'app_account_order')]
    public function show(string $reference): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order || $this->getUser() !== $order->getUser()) {
            return $this->redirectToRoute('app_account_orders');
        }

        return $this->render('account/order.html.twig', [
            'order' => $order
        ]);
    }
}
