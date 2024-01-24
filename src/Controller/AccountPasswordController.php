<?php

namespace App\Controller;
use App\Form\ChangPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_account_password')]
    public function index(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $old_psw = $form->get('old_password')->getData();
            // dd($old_psw);
            
            if ($encoder->isPasswordValid($user, $old_psw)) {
                $new_psw = $form->get('new_password')->getData();
                $password = $encoder->hashPassword($user, $new_psw);
                
                $user->setPassword($password);
                $entityManager->flush();
                $notification = "Votre mot de passe a bien été mis a jour.";
            
            } else {
                $notification = "Votre mdp actuel n'est pas le bon";
            }
        }
        return $this->render('account/password.html.twig',[
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
