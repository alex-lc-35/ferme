<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Enum\PickupDay;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Actions, Action, Crud, Filters};
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Collection\{FieldCollection, FilterCollection};
use EasyCorp\Bundle\EasyAdminBundle\Dto\{SearchDto, EntityDto};
use EasyCorp\Bundle\EasyAdminBundle\Field\{BooleanField,
    CollectionField,
    DateTimeField,
    ChoiceField,
    IdField,
    MoneyField,
    TextField};
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderCrudController extends AbstractCrudController
{
    public function __construct(private OrderRepository $orderRepository) {}

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Date de commande'),
            TextField::new('user.firstName', 'Prénom'),
            TextField::new('user.lastName', 'Nom'),
            MoneyField::new('total', 'Prix total')->setCurrency('EUR'),

            ChoiceField::new('pickup')
                ->setLabel('Jour de retrait')
                ->renderAsBadges()
                ->formatValue(fn($value) => match($value) {
                    \App\Enum\PickupDay::TUESDAY => 'Mardi',
                    \App\Enum\PickupDay::THURSDAY => 'Jeudi',
                    default => $value,
                }),

            BooleanField::new('done', 'Traitée')->renderAsSwitch(true),
        ];

        if (Crud::PAGE_DETAIL === $pageName) {
            return [

                CollectionField::new('productOrders',)
                    ->onlyOnDetail()
                    ->setLabel(false)
                    ->setTemplatePath('admin/order_product_orders.html.twig'),
            ];}



            return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE, Action::NEW)
            ->addBatchAction(
                Action::new('markDeleted', 'Supprimer commande(s)')
                    ->linkToCrudAction('markAsDeleted')
                    ->addCssClass('btn-danger')
            )
            ->add(Crud::PAGE_INDEX, Action::DETAIL) // on ajoute l'action détail
            ->remove(Crud::PAGE_INDEX, Action::EDIT) // on supprime le bouton modifier
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
        return $action->setLabel('Détails')->setIcon('fa fa-eye');
    });
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(
                BooleanFilter::new('done')
                    ->setLabel('Traitée')
            )
            ->add(
                ChoiceFilter::new('pickup')
                    ->setLabel('Jour de retrait')
                    ->setChoices([
                        'Mardi' => PickupDay::TUESDAY,
                        'Jeudi' => PickupDay::THURSDAY,
                    ])
            );
    }

    public function markAsDeleted(
        Request $request,
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $entityManager // on l'injecte ici
    ): RedirectResponse {
        $entityIds = $request->request->all('batchActionEntityIds', []);

        if (empty($entityIds)) {
            $this->addFlash('warning', 'Aucune commande sélectionnée.');
            return $this->redirectBackToIndex($context, $adminUrlGenerator);
        }

        $orders = $this->orderRepository->findBy(['id' => $entityIds]);

        $nonDeletable = [];

        foreach ($orders as $order) {
            if (!$order->isDone()) {
                $nonDeletable[] = $order->getId();
                continue;
            }

            $order->setIsDeleted(true);
        }

        $entityManager->flush(); // méthode Doctrine officielle & propre

        if (!empty($nonDeletable)) {
            $this->addFlash('warning', sprintf(
                'Ces commandes n\'ont pas été supprimées car elles sont encore en attente : %s',
                implode(', ', $nonDeletable)
            ));
        } else {
            $this->addFlash('success', 'Commande(s) supprimée(s) !');
        }

        return $this->redirectBackToIndex($context, $adminUrlGenerator);
    }


    private function redirectBackToIndex(AdminContext $context, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        return $this->redirect($context->getReferrer() ?? $adminUrlGenerator
            ->setController(self::class)
            ->setAction('index')
            ->generateUrl());
    }


}
