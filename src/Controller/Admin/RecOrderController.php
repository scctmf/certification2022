<?php 

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use App\MesServices\CartService\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecOrderController extends AbstractController
{
    /**
     * @Route("admin/rec/order",name="admin_rec_order")
     */
    public function rec(CartService $cartService,Request $request,
                            EntityManagerInterface $em)
    {
        $detailCart = $cartService->getDetailedCartItems();

        $totalCart = $cartService->getTotal();

        $address = new Address();

        /** @var User $user */
        $user = $this->getUser();

        if($user)
        {
            if($user->getAddress())
            {
                $address = $user->getAddress();
            }
        }

        $form = $this->createForm(AddressType::class,$address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$user->getAddress())
            {
                $address->setUser($user);
                $em->persist($address);
            }

            $this->addFlash("success","L'adresse a bien été configurée.");
            $em->flush();

            return $this->redirectToRoute("admin_rec_order");
            
        }

        return $this->render("admin/rec_order.html.twig",[
            'detailCart' => $detailCart,
            'form' => $form->createView(),
            'totalCart' => $totalCart
        ]);

    }
}