<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'front_home_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ProductRepository $repository, Request $request): Response
    {
        $pagination = $repository->paginateProductsOrderByUpdatedAt($request->query->getInt('page', 1));

        return $this->render('front/home/index.html.twig', [
            'products' => $pagination,
        ]);
    }

    #[Route('/detail/{slug}', name: 'show', methods: ['GET'])]
    public function show(string $slug, ProductRepository $repository): Response
    {
        $product = $repository->findProductWithCategoryBySlug($slug);

        return $this->render('front/home/show.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/conditions-du-site', name: 'terms_conditions')]
    public function terms()
    {
        return $this->render('front/home/terms.html.twig');
    }
}
