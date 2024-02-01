<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $entityManagerntityManager;
    private $adminUrlGenerator;
    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        $updateDelivery = Action::new('updateDelivery', 'En Cours de Livraison', 'fa-solid fa-truck')->linkToCrudAction('updateDelivery');
        $updatePreparation = Action::new('updatePreparation', 'Preparation en cours', 'fa-solid fa-hammer')->linkToCrudAction('updatePreparation');
        

        return $actions
            ->add('detail', $updateDelivery)
            ->add('detail', $updatePreparation)
            
            ->add('index', 'detail');
    }

    public function updatePreparation(AdminContext $context)
    {
        $order=$context->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();

        $url = $this->adminUrlGenerator
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();


        $mail = new Mail();
        $content = "Bonjour ".$order->getUser()->getFirstName().'<br>Merci pour votre Commande sur la premiere boutique "Made in France" <br><hr>
        Profitez de notre <a href="http://178.33.104.51:8000/nos_produits">boutique</a> renouvelée regulierement pour vous proposer le meilleur de notre savoir faire nationale.';
        $mail->send( $order->getUser()->getEmail(),$order->getUser()->getFirstName(), 'Votre commande "La Boutique Francaise" est bien en cours de préparation', $content);

        $this->addFlash('notice', "<span style='color:green;'><strong>La Commande ".$order->getReference()." est bien en cours de préparation</strong></span>");

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order=$context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        $url = $this->adminUrlGenerator
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        $mail = new Mail();
        $content = "Bonjour ".$order->getUser()->getFirstName().'<br>Merci pour votre Commande sur la premiere boutique "Made in France" <br><hr>
        Profitez de notre <a href="http://178.33.104.51:8000/nos_produits">boutique</a> renouvelée regulierement pour vous proposer le meilleur de notre savoir faire nationale.';
        $mail->send( $order->getUser()->getEmail(),$order->getUser()->getFirstName(), 'Votre commande "La Boutique Francaise" a bien été EXPEDIEE', $content);

        $this->addFlash('notice', "<span style='color:blue;'><strong>Le Colis ".$order->getReference()." a bien été EXPEDIE</strong></span>");

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id'=>'DESC']);
    } 

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passée le'),
            TextField::new('user.getFullname', 'Client'),
            TextEditorField::new('delivery', 'Adresse de Livraison')->onlyOnDetail()->formatValue(function ($value, $entity) {
                return $value; // Désactive l'échappement HTML
            }),
            MoneyField::new('total', 'Montant')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de Port')->setCurrency('EUR'),

            ChoiceField::new('state')->setChoices([
                'Non Payée' => 0,
                'Payée' => 1,
                'En Cours de Preparation' => 2,
                'Colis en Chemin vers le Domicile' => 3,

            ]),
            ArrayField::new('orderDetails', 'Prodits achetés')->hideOnIndex(),
        ];
    }
}
