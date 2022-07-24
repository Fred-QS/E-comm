<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchType;
use App\Service\CategorySearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'app_products')]
    public function index(Request $request): Response
    {
        $search = new CategorySearchService();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$search = $form->getData(); Pas besoin car $search est déjà lié au formulaire
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        } else {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('products/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product')]
    public function show(string $slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

        if (!$product) {
            throw $this->createNotFoundException('Ce produit n\'existe pas.');
        }

        return $this->render('products/product.html.twig', [
            'product' => $product,
            'products' => $products
        ]);
    }
}
