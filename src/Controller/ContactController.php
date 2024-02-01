<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form= $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->addFlash('notice', 'Votre message a bien été pris en compte, nous reviendrons vers vous dès que possible !');

            
            //envoi un mail aux admins avec le text du contact
                $mail = new Mail();
                $content = "Bonjour <br>vous avez recu un nouveau message de la part de ".$form->get('nom')->getData()." ".$form->get('prenom')->getData()." sur La Boutique Francaise <br><hr>
                Voici le message : <br><br>".$form->get('content')->getData()."<br> <hr> <br>Répondez a cette adresse : ".$form->get('email')->getData();
                $mail->send("chewie.59@hotmail.fr", "Nom du site ", 'Nouveau message du formulaire de contact', $content);

        }

        return $this->render('contact/index.html.twig',[
            'form'=> $form->createView(),
        ]);
    }
}
