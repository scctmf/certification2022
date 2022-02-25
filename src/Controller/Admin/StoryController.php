<?php

namespace App\Controller\Admin;


use App\Entity\Story;
use App\Form\StoryType;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/story")
 */

class StoryController extends AbstractController
{
    
     /**
     * @Route("/", name="story_index", methods={"GET"})
     */
    public function index(StoryRepository $storyRepository): Response
    {

        //envoie dans la vue les catégories qu'on vient de récupérer
        return $this->render('admin/story/index.html.twig', [
            //Fait appel à la base de données et de récupère la liste des catégories.
            'story' => $storyRepository->findAll(),
        ]);
           
    }

      /**
     * @Route("/new", name="story_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $story = new Story();
        //pour créer le formulaire
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($story);
            $entityManager->flush();

            return $this->redirectToRoute('story_index', [], Response::HTTP_SEE_OTHER);
        }
        //envoie dans le formulaire les catégories qu'on vient de récupérer    
        return $this->renderForm('admin/story/new.html.twig', [
            'story' => $story,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="story_show", methods={"GET"})
     */
    public function show(Story $story): Response
    {
        return $this->render('admin/story/show.html.twig', [
            'story' => $story,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="story_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Story $story, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StoryType::class, $story);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           
            $entityManager->flush();

            return $this->redirectToRoute('story_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/story/edit.html.twig', [
            'story' => $story,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="story_delete", methods={"POST"})
     */
    public function delete(Request $request, Story $story, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$story->getId(), $request->request->get('_token'))) {
            $entityManager->remove($story);
            $entityManager->flush();
        }

        return $this->redirectToRoute('story_index', [], Response::HTTP_SEE_OTHER);
    }
}
