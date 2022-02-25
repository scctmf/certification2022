<?php

namespace App\Controller\Admin;

use App\Entity\CommandShop;
use App\Form\CommandShopType;
use App\Repository\CommandShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/command/shop")
 */
class CommandShopController extends AbstractController
{
    /**
     * @Route("/", name="command_shop_index", methods={"GET"})
     */
    public function index(CommandShopRepository $commandShopRepository): Response
    {
        $user = $this->getUser();

        return $this->render('admin/command_shop/index.html.twig', [
            'command_shops' => $commandShopRepository->findBy(['user' => $user]),
        ]);
    }

    /**
     * @Route("/new", name="command_shop_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commandShop = new CommandShop();
        $form = $this->createForm(CommandShopType::class, $commandShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commandShop);
            $entityManager->flush();

            return $this->redirectToRoute('admin/command_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/command_shop/new.html.twig', [
            'command_shop' => $commandShop,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="command_shop_show", methods={"GET"})
     */
    public function show(CommandShop $commandShop): Response
    {
        return $this->render('admin/command_shop/show.html.twig', [
            'command_shop' => $commandShop,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="command_shop_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CommandShop $commandShop, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandShopType::class, $commandShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin/command_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/command_shop/edit.html.twig', [
            'command_shop' => $commandShop,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="command_shop_delete", methods={"POST"})
     */
    public function delete(Request $request, CommandShop $commandShop, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commandShop->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commandShop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin/command_shop_index', [], Response::HTTP_SEE_OTHER);
    }
}
