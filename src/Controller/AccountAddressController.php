<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountAddressController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-compte/mes-adresses', name: 'app_account_address')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    #[Route('/mon-compte/ajouter-une-adresse', name: 'app_account_add_address')]
    public function add(Request $request, CartService $cart): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            if ($cart->get()) {
                return $this->redirectToRoute('app_order');
            }

            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/form-address.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/mon-compte/modifier-une-adresse/{id}', name: 'app_account_edit_address')]
    public function edit(Request $request, int $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->find($id);
        if (!$address || $address->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();
            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/form-address.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/mon-compte/supprimer-une-adresse/{id}', name: 'app_account_delete_address')]
    public function delete(int $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->find($id);

        if ($address && $address->getUser() === $this->getUser()) {

            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }


        return $this->redirectToRoute('app_account_address');
    }
}
