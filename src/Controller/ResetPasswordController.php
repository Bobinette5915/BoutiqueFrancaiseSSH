<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie/password', name: 'app_reset_password')]
    public function index(Request $request): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));


            if ($user) {
                //etape 1, enregistret la demande en BDD avec user token et createdAt
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                //etape 2, envoyer mail avec lien pour reset password
                $url = $this->generateUrl("app_update_password", [
                    'token' => $reset_password->getToken()
                ]);

                $mail = new Mail();
                $content = "Bonjour " . $user->getFirstName() . "<br>vous avez demander la réinitialisation de votre mot de passe sur La Boutique Francaise <br><hr>
                Cliquez <a href='" . $url . "'>ICI pour mettre à jour votre Mot de Passe.</a>";
                $mail->send($user->getEmail(), $user->getFirstName() . ' ' . $user->getLastName(), 'Réinitialiser votre Mot de passe sur La Boutique Francaise', $content);

                $this->addFlash('notice', 'Un mail avec la procedure de réinitialisation viens de vous etre envoyé, merci de consulter votre messagerie');
            } else {
                $this->addFlash('notice', 'Compte Utilisateur inconnue');
            }
        }

        return $this->render('reset_password/index.html.twig', []);
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'app_update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $encoder): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {
        }
        //verifier le createdAt = now - 3h

        $now = new \DateTimeImmutable();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {

            $this->addFlash('notice', 'Oups, trop tard, Votre demande de mot de passe a expirée. Merci de relancer la procedure');
            return $this->redirectToRoute('app_reset_password');
        }

        // rendre une vue avec mot de passe et confirmer le mot de passe

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $new_psw = $form->get('new_password')->getData();
            

            // encoder le nouveau mot de passe
            $password = $encoder->hashPassword($reset_password->getUser(), $new_psw);

            $reset_password->getUser()->setPassword($password);
            // flush en BDD
            $this->entityManager->flush();

            // Redirection de l'utilisateur vers la page de connexion
            $this->addFlash('notice', 'Votre mot de passe a bien été renouvelé, Vous pouvez maintenant vous connecter');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
