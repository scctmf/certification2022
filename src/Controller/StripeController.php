<?php 

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\CommandShop;
use Stripe\Checkout\Session;
use App\Entity\CommandListProduct;
use App\MesServices\MailerService;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Entity\CommandDeliveryAddress;
use App\Entity\ContentListCommandShop;
use App\MesServices\CommandShopService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandShopRepository;
use App\MesServices\CartService\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\MesServices\CartService\CartRealProduct;
use Doctrine\Common\Collections\Expr\Value;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class StripeController extends AbstractController
{
    /**
     * @Route("/create-checkout-session",name="create_checkout_session")
     */
    public function createSession(CartService $cartService,CommandShopService $commandShopService)
    {
        Stripe::setApiKey('sk_test_29fKcvLYxazX0nWP1cRVhzOU00hS03d0rr');

        $domain = 'https://localhost:8000';

        /** @var User $user */
        $user = $this->getUser();

        $commandShopService->create($user);

        /** @var CartRealProduct[] $detailCart */
        $detailCart = $cartService->getDetailedCartItems();

        $productForStripe = [];

        foreach($detailCart as $item)
        {
            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item->getProduct()->getPrice(),
                    'product_data' => [
                        'name' => $item->getProduct()->getName(),
                        'images' => [
                            $domain . $item->getProduct()->getImagePath()
                        ]
                    ]
                ],
                'quantity' => $item->getQty()
            ];
        }

        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => [
                'card',
            ],
            'line_items' => [
                $productForStripe
            ],
            'mode' => 'payment',
              'success_url' => $domain . '/paiementReussi/',
              'cancel_url' => $domain . '/paiementechoue',
          ]);

          return $this->redirect($checkout_session->url);
    }

    // /**
    //  * @Route("/redirectionPaiementReussi/{id}/{cart}", name="redirection_paiement_reussi")
    //  */
    // public function redirectionPaiementReussi(int $id ,$cart, LoginLinkHandlerInterface $loginLinkHandler, UserRepository $userRepository, Request $request)
    // {   
    //     $user = $userRepository->find($id);
    //     $loginLinkDetails = $loginLinkHandler->createLoginLink($user);
    //     $loginLink = $loginLinkDetails->getUrl();
    //     return $this->redirect($loginLink);
    // }

    /**
     * @Route("/paiementReussi", name="payment_success")
     */
    public function paymentSuccess(ProductRepository $productRepository, CommandShopRepository $commandShopRepository,MailerService $mailerService,EntityManagerInterface $em,
    CartService $cartService)
    {
        //Je recupere le user
        /** @var User $user */
        $user = $this->getUser();

        $command = $commandShopRepository->findOneBy([
            'user' => $user
        ],[
            'createdAt' => 'DESC'
        ]);

        $command->setIsPayed(1);

        $em->flush();

        //Envoyer un mail au client avec le recap de la commande
        $mailerService->sendCommandMail($user,$command);
        $listeItem = $cartService->getDetailedCartItems();
        $products = $productRepository->findAll();
        $cartService->emptyCart();

        $this->addFlash("success","Votre commande a bien ??t?? pris en compte.");
        //dd($listeItem);
        return $this->render('customer/story.html.twig',[
            'items' => $listeItem,
                ]);
    }

    // public function getAllProductFromCart($products, $items){
    //     $result;
    //     foreach ($items as $key => $value) {
    //         if($value->getAllProductFromCart)
    //     }
    // }

     /**
     * @Route("/paiementechoue", name="payment_cancel")
     */
    public function paymentCancel()
    {
        $this->addFlash("info","Votre commande n'a pu aboutir. Vous pouvez essayer avec une mani??re de paiement.");
        return $this->redirectToRoute("cart_detail");
    }
}