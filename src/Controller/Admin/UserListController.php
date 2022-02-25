<?php

namespace App\Controller\Admin;

use app\Entity\User;
use App\Repository\UserRepository;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserListController extends AbstractController
{
    /**
     * @Route("/admin/user_list", name="admin_user_list")
     */
    public function index(UserRepository $userRepository, AddressRepository $addressRepository): Response
    {
        $users = $userRepository->findAll();
        $address = $addressRepository->findAll();


          //envoie dans la vue les catégories qu'on vient de récupérer
          return $this->render('admin/userList/user_list.html.twig', [
            //Fait appel à la base de données et de récupère la liste des catégories.
            'users' => $users,
            'address' => $address,
        ]);
    }

    // /**
    //  * @Route("/{id}", name="userList_show", methods={"GET"})
    //  */
    // public function show(UserRepository $userRepository, AddressRepository $addressRepository): Response
    // {
    //     return $this->render('admin/userList/show.html.twig', [
    //         'users' => $users,
    //         'address' => $address,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="story_edit", methods={"GET", "POST"})
    //  */
    // public function edit(Request $request, Story $story, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(StoryType::class, $story);


    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

           
    //         $entityManager->flush();

    //         return $this->redirectToRoute('story_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('admin/story/edit.html.twig', [
    //         'story' => $story,
    //         'form' => $form,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="story_delete", methods={"POST"})
    //  */
    // public function delete(Request $request, Story $story, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$story->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($story);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('story_index', [], Response::HTTP_SEE_OTHER);
    // }
}
