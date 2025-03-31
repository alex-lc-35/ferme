<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Enum\PickupDay;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Actions, Action, Crud, Filters};
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Collection\{FieldCollection, FilterCollection};
use EasyCorp\Bundle\EasyAdminBundle\Dto\{SearchDto, EntityDto};
use EasyCorp\Bundle\EasyAdminBundle\Field\{CollectionField,
    DateTimeField,
    ChoiceField,
    AssociationField,
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

            ChoiceField::new('status')
                ->setLabel('Statut')
                ->renderAsBadges()
                ->formatValue(fn($value) => match($value) {
                    \App\Enum\OrderStatus::PENDING => 'En attente',
                    \App\Enum\OrderStatus::DONE => 'Traitée',
                    default => $value,
                }),
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
                ChoiceFilter::new('status')
                    ->setLabel('Statut')
                    ->setChoices([
                        'En attente' => OrderStatus::PENDING,
                        'Traitée' => OrderStatus::DONE,
                    ])
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

    public function markAsDeleted(Request $request, AdminContext $context, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        $entityIds = $request->request->all('batchActionEntityIds', []);

        if (empty($entityIds)) {
            $this->addFlash('warning', 'Aucune commande sélectionnée.');
            $url = $adminUrlGenerator
                ->setController(self::class)
                ->setAction('index')
                ->generateUrl();
            return $this->redirect($url);
        }

        $orders = $this->orderRepository->findBy(['id' => $entityIds]);

        foreach ($orders as $order) {
            $order->setIsDeleted(true);
        }

        $this->orderRepository->flush();

        $this->addFlash('success', 'Commande(s) supprimée(s) !');

        $referrer = $context->getReferrer();
        if ($referrer) {
            return $this->redirect($referrer);
        }

        $url = $adminUrlGenerator
            ->setController(self::class)
            ->setAction('index')
            ->generateUrl();
        return $this->redirect($url);
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        // On récupère le QueryBuilder EasyAdmin de base
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // On ajoute notre condition personnalisée
        $qb->andWhere('entity.isDeleted = :deleted')
            ->setParameter('deleted', false);

        return $qb;
    }

}
