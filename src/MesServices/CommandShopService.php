<?php 

namespace App\MesServices;

use App\Entity\User;
use App\Entity\CommandShop;
use App\Entity\CommandListProduct;
use App\Entity\CommandDeliveryAddress;
use App\Entity\ContentListCommandShop;
use App\MesServices\CartService\CartRealProduct;
use App\MesServices\CartService\CartService;
use Doctrine\ORM\EntityManagerInterface;

class CommandShopService 
{
    private $em;
    private $cartService;

    public function __construct(CartService $cartService, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->cartService =$cartService;
    }
    public function create(User $user)
    {
         //Je dois creer  une commande
         $commandShop = new CommandShop();
         $commandShop->setUser($user);
         $commandShop->setTotal($this->cartService->getTotal());
         $this->em->persist($commandShop);
 
 
         //Je dois creer une adresse lié a la commande
         $addressUser = $user->getAddress();
 
         $commandDeliveryAddress = new CommandDeliveryAddress();
         $commandDeliveryAddress->setCommandShop($commandShop);
         $commandDeliveryAddress->setCountry($addressUser->getCountry());
         $commandDeliveryAddress->setCity($addressUser->getCity());
         $commandDeliveryAddress->setPostalCode($addressUser->getPostalCode());
         $commandDeliveryAddress->setStreet($addressUser->getStreet());
         $commandDeliveryAddress->setCommentary($addressUser->getCommentary());
 
         $this->em->persist($commandDeliveryAddress);
 
         //Je dois creer une liste de produits lié a la commande
         $listProduct = new CommandListProduct();
         $listProduct->setCommandShop($commandShop);
 
         $this->em->persist($listProduct);
 
 
         //Je dois remplir cette liste
         /** @var CartRealProduct[] $detailCart */
         $detailCart = $this->cartService->getDetailedCartItems();
 
         foreach($detailCart as $item)
         {
             $contentList = new ContentListCommandShop();
             $contentList->setProduct($item->getProduct());
             $contentList->setListProduct($listProduct);
             $contentList->setQuantity($item->getQty());
             $this->em->persist($contentList);
         }
 
         $this->em->flush();

         return $commandShop;
     }
 
       

}