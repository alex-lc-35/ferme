<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Enum\PickupDay;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Actions, Action};
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Collection\{FieldCollection, FilterCollection};
use EasyCorp\Bundle\EasyAdminBundle\Dto\{SearchDto, EntityDto};
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    DateTimeField,
    ChoiceField,
    AssociationField,
    IdField,
    MoneyField,
    TextField
};
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
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Date de commande'),
            TextField::new('user.firstName', 'Prénom'),
            TextField::new('user.lastName', 'Nom'),
            MoneyField::new('total', 'Prix total')->setCurrency('EUR'),
            ChoiceField::new('pickup')
                ->setLabel('Jour de retrait')
                ->renderAsBadges()
                ->setChoices(array_combine(
                    array_map(fn($v) => $v->name, PickupDay::cases()),
                    PickupDay::cases()
                )),
            ChoiceField::new('status')
                ->setLabel('Statut')
                ->renderAsBadges()
                ->setChoices(array_combine(
                    array_map(fn($v) => $v->name, OrderStatus::cases()),
                    OrderStatus::cases()
                )),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE)
            ->addBatchAction(
                Action::new('markDeleted', 'Supprimer commande(s)')
                    ->linkToCrudAction('markAsDeleted')
                    ->addCssClass('btn-danger')
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
        return $this->orderRepository->createNonDeletedQueryBuilder('o');
    }
}
