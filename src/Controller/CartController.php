<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function index(CartService $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/ajouter-au-panier/{id}', name: 'app_add_to_cart')]
    public function add(CartService $cart, $id): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/vider-le-panier', name: 'app_remove_cart')]
    public function remove(CartService $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_products');
    }

    #[Route('/supprimer-du-panier/{id}', name: 'app_delete_from_cart')]
    public function delete(CartService $cart, $id): Response
    {
        $cart->delete($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/enlever-une-quantite/{id}', name: 'app_decrease_product')]
    public function decrease(CartService $cart, $id): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('app_cart');
    }
}
