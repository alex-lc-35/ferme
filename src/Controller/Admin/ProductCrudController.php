<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Enum\ProductUnit;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addJsFile('js/admin/product-form.js')
            ->addCssFile('css/admin/custom.css');
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('🥕 Produit')
            ->setEntityLabelInPlural('🥕 Produits')
            ->setPageTitle(Crud::PAGE_INDEX, '🥕 Produits')
            ->setPaginatorPageSize(10)
            ->setDefaultSort(['updatedAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnIndex(),

            TextField::new('name')->setLabel('Nom'),

            MoneyField::new('priceInEuros')
                ->setLabel('Prix (€)')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setNumDecimals(2)
                ->setFormTypeOption('required', true),

            ChoiceField::new('unit')
                ->setLabel('Unité')
                ->setChoices([
                    'Pièce' => ProductUnit::PIECE,
                    'Botte' => ProductUnit::BUNDLE,
                    'Bouquet' => ProductUnit::BUNCH,
                    'Litre' => ProductUnit::LITER,
                    'Kilo' => ProductUnit::KG,
                ])

            ->renderAsBadges([
        ProductUnit::PIECE->value => 'primary',
        ProductUnit::BUNDLE->value => 'success',
        ProductUnit::BUNCH->value => 'warning',
        ProductUnit::LITER->value => 'info',
        ProductUnit::KG->value => 'secondary',
    ])
        ->formatValue(function ($value, $entity) {
            return match ($value?->value ?? null) {
                'PIECE' => 'Pièce',
                'BUNDLE' => 'Botte',
                'BUNCH' => 'Bouquet',
                'LITER' => 'Litre',
                'KG' => 'Kilo',
                default => $value?->value ?? '',
            };
        }),

            NumberField::new('inter')
                ->onlyOnForms()
                ->setLabel('Intervalle (en grammes)')
                ->setFormTypeOption('attr', [
                    'step' => 0.01,
                    'min' => 0,
                ])
                ->setFormTypeOption('html5', true)
                ->setFormTypeOption('row_attr', ['class' => 'inter-wrapper']),

            BooleanField::new('hasStock')
                ->hideOnIndex()
                ->setLabel('Stock')
                ->setFormTypeOption('row_attr', ['class' => 'has-stock-wrapper flex-stock-row']),

            IntegerField::new('stock')
                ->onlyOnForms()
                ->setFormTypeOption('attr', [
                    'min' => 0,
                ])
                ->setFormTypeOption('row_attr', ['class' => 'stock-wrapper']),

            BooleanField::new('limited')->setLabel('Qté Limitée'),

            BooleanField::new('discount')
                ->hideOnIndex()
                ->setLabel('Promo'),

            TextField::new('discountText')
                ->onlyOnForms()
                ->setLabel('Texte Promo')
                ->setFormTypeOption('row_attr', ['class' => 'discountText-wrapper']),

            BooleanField::new('isDisplayed')->setLabel('Affiché'),

            ImageField::new('image')
                ->setBasePath('/uploads/images')
                ->setUploadDir('public/uploads/images')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setFormTypeOptions([
                    'required' => Crud::PAGE_NEW === $pageName,
                ])
                ->addCssClass('avatar-image'),

            AssociationField::new('user')
                ->hideOnForm()
                ->hideOnIndex(),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        /** @var Product $product */
        $product = new Product();
        $product->setUser($this->security->getUser());

        return $product;
    }


    public function configureActions(Actions $actions): Actions
    {
        $returnAction = Action::new('Retour')
            ->linkToUrl('/admin/product');

        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $a) => $a->setLabel('Ajouter un produit'))
            ->disable(Action::DELETE)
            ->addBatchAction(
                Action::new('markDeleted', 'Supprimer produit(s)')
                    ->linkToCrudAction('markAsDeleted')
                    ->addCssClass('btn-danger')
            )
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_NEW, $returnAction)
            ->add(Crud::PAGE_EDIT, $returnAction)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);

    }

    public function markAsDeleted(
        Request $request,
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $entityManager
    ): RedirectResponse {
        $entityIds = $request->request->all('batchActionEntityIds', []);

        if (empty($entityIds)) {
            $this->addFlash('warning', 'Aucun produit sélectionné.');
            return $this->redirectBackToIndex($context, $adminUrlGenerator);
        }

        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->findBy(['id' => $entityIds]);

        $filters = $entityManager->getFilters();
        if ($filters->isEnabled('soft_delete')) {
            $filters->disable('soft_delete');
        }

        $nonDeletable = [];

        foreach ($products as $product) {
            if ($product->getNonDeletedProductOrders()->count() > 0) {
                $nonDeletable[] = $product->getName();
                continue;
            }

            $product->setIsDeleted(true);
        }

        $filters->enable('soft_delete');
        $entityManager->flush();

        if (!empty($nonDeletable)) {
            $this->addFlash('warning', sprintf(
                'Les produits suivants n\'ont pas été supprimés car ils sont liés à des commandes : %s',
                implode(', ', $nonDeletable)
            ));
        } else {
            $this->addFlash('success', 'Produit(s) supprimé(s) !');
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
