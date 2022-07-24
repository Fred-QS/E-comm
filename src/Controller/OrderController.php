<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/ma-commande', name: 'app_order')]
    public function index(CartService $cart): Response
    {
        $user = $this->getUser();
        if (!$user->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_account_add_address');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $user
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/ma-commande/recapitulatif', name: 'app_order_recap', methods: ['POST'])]
    public function add(CartService $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);
        $carrier = $delivery_content = null;

        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTimeImmutable();
            $carrier = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();

            $delivery_content = $delivery->getFirstname() . ' ' . $delivery->getLastname();
            $delivery_content .= '<br/>' . $delivery->getPhone();
            $delivery_content .= ($delivery->getCompany()) ? '<br/>' . $delivery->getCompany() : '';
            $delivery_content .= '<br/>' . $delivery->getAddress();
            $delivery_content .= '<br/>' . $delivery->getPostal() . ' ' . $delivery->getCity();
            $delivery_content .= '<br/>' . $delivery->getCountry();

            // Enregistrer la commande Order()
            $order = new Order();
            $reference = date_format($date, "dmY") . '-' . uniqid('', true);
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setDelivery($delivery_content);
            $order->setState(0);

            $this->entityManager->persist($order);

            // Enregistrer les produits dans OrderDetails()

            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();

            return $this->render('order/recap.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carrier,
                'delivery' => $delivery_content,
                'reference' => $order->getReference()
            ]);
        }

        return $this->redirectToRoute('app_cart');
    }
}
