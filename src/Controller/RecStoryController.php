<?php

namespace App\Controller;

use App\Entity\Story;
use App\Repository\StoryRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecStoryController extends AbstractController
{
   /**
     * @Route("/story", name="customer_story", methods={"GET", "POST"})
     */
    public function story(CategoryRepository $categoryRepository,ProductRepository $productRepository, StoryRepository $storyRepository): Response
    {
        $categories = $categoryRepository->findBy([]);

        $products = $productRepository->findBy([]);

        $story = $storyRepository->findBy([]);

          //envoie dans le formulaire les histoires qu'on vient de récupérer   

        return $this->render('customer/story.html.twig',[
            'categories' => $categories,
            'story' => $categories,
            'products' => $products
        ]);

       
       
    }
}
