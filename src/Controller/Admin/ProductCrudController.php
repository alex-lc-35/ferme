<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Enum\ProductUnit;
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
                    'step' => 0.1,
                    'min' => 0,
                ])
                ->setFormTypeOption('html5', true)
                ->setFormTypeOption('row_attr', ['class' => 'inter-wrapper']),

            BooleanField::new('hasStock')->hideOnIndex()->setLabel('Stock'),

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
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),

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

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addJsFile('js/admin/product-form.js');
    }

    public function configureActions(Actions $actions): Actions
    {
        $returnAction = Action::new('Retour')
            ->linkToUrl('/admin/product');

        return $actions
            ->add(Crud::PAGE_NEW, $returnAction)
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $a) => $a->setLabel('Ajouter un produit'));

    }


}
