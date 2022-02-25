<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\StoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerHomeController extends AbstractController
{
    /**
     * @Route("/", name="customer_home")
     */
    public function index(CategoryRepository $categoryRepository,ProductRepository $productRepository, StoryRepository $storyRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $products = $productRepository->findBy([],null,8);

        $story = $storyRepository->findAll();

        return $this->render('customer/index.html.twig',[
            'categories' => $categories,
            'story' => $categories,
            'products' => $products
        ]);
    }

   
}
