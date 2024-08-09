<?php

namespace App\Controller\Front;

use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use App\Service\CartService;
use App\Service\EmailService;
use App\Service\InvoiceService;
use App\Service\OrderService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/commande', name: 'front_order_')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $em, CartService $cartService, OrderService $orderService): Response
    {
        $user = $this->getUser();
        $cart = $cartService->getCart();

        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide');
            return $this->redirectToRoute('front_cart_index');
        }

        $order = $orderService->createOrder($user, $cart);

        $em->persist($order);
        $em->flush();

        $cartService->emptyCart();

        $this->addFlash('success', 'la commande a été passé');
        // cette route attend un parametre dynamique 'id' comment lui passer avec une valeur
        return $this->redirectToRoute('front_order_confirmation', ['id' => $order->getId()]);
    }


    #[Route('/confirmation/{id}', name: 'confirmation', methods: ['GET'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function confirmation(int $id, OrderRepository $repository): Response
    {
        $order = $repository->findOrderWithRelations($id);

        return $this->render('front/order/confirmation.html.twig', [
            'order' => $order
        ]);
    }

    #[Route('/validation/{id}', name: 'validated', methods: ['GET'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function validated(int $id, OrderRepository $repository, EmailService $emailService, EntityManagerInterface $em, InvoiceService $invoiceService)
    {
        // if (!$paymentService->processPayment($order)) {
        //     $this->addFlash('error', 'Ne paiement n'a pas été effectué!');
        //     return $this->redirectToRoute('front_order_confirmation', ['id' => $order->getId()]);
        // }

        $order = $repository->findOrderWithRelations($id);

        $order
            ->setStatus(OrderStatus::PAID)
            ->setPaidAt(new DateTimeImmutable());

        $em->flush();

        $emailService->sendOrderConfirmationEmail($order);
        $pdf = $invoiceService->generateInvoice($order);

        $invoiceService->saveInvoice($order, $pdf);

        $this->addFlash('success', 'Votre commande a bien été passée, vous allez recevoir un email de confirmation');

        return $this->redirectToRoute('front_home_index');
    }
}
