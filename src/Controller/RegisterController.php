<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email) {
                $password =  $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $entityManager->persist($user);
                $entityManager->flush();

                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstName()."<br>Bienvenu sur la premiere boutique Made in France <br><hr>
                Profitez de notre boutique renouvelée regulierement pour vous proposer le meilleur de notre savoir faire nationale.";
                $mail->send( $user->getEmail(),$user->getFirstName(), 'Bienvenue sur La Boutique Francaise', $content);

                $notification = "Votre inscription s'est correcrement déroulé. Vous pouvez des a present vpous connecter à votre compte";
            } else {
                $notification = "Cet adresse mail est deja utilisée";
            }
        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
