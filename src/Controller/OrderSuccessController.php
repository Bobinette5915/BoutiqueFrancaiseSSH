<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index($stripeSessionId, Cart $cart): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        };
        if ($order->getState()== 0) {
            //Vider la session cart
            $cart->remove();
            //modifier le statue state de la commande en le passant de 0 à 1 pour savoir si il est payé
            $order->setState(1);
            $this->entityManager->flush();
            //envoyer un mail pour confirmer la commande
            $mail = new Mail();
            $content = "Bonjour ".$order->getUser()->getFirstName().'<br>Merci pour votre Commande sur la premiere boutique "Made in France" <br><hr>
            Profitez de notre <a href="http://178.33.104.51:8000/nos_produits">boutique</a> renouvelée regulierement pour vous proposer le meilleur de notre savoir faire nationale.';
            $mail->send( $order->getUser()->getEmail(),$order->getUser()->getFirstName(), 'Votre commande "La Boutique Francaise" a bien été validée', $content);


            //afficher les infos de la commande de m'utilisateur

        }
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
