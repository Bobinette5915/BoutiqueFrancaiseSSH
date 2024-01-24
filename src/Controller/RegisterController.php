<?php

namespace App\Controller;

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
    // private $EntityManager;
    // public function __construct(EntityManagerInterface $entityManager){
    //     $this->EntityManager = $entityManager;
    // }
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $encoder): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $password =  $encoder->hashPassword($user,$user->getPassword());
            $user->setPassword($password);

            $entityManager->persist($user); 
            $entityManager->flush();
        

        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form->createView(),

        ]);
    }
}
