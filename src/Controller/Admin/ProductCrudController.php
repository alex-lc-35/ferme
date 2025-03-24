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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

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
            IntegerField::new('price')->setLabel('Prix'),

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

            IntegerField::new('inter')->hideOnIndex(),

            BooleanField::new('hasStock')->hideOnIndex()->setLabel('Stock'),

            IntegerField::new('stock')->onlyOnForms(),

            BooleanField::new('isDisplayed')->setLabel('Affiché'),
            BooleanField::new('limited')->setLabel('Qté Limitée'),
            BooleanField::new('discount')->hideOnIndex()->setLabel('Promo'),
            TextField::new('discountText')->onlyOnForms()->setLabel('Text Promo'),

            ImageField::new('image')
                ->setBasePath('/uploads/images')
                ->setUploadDir('public/uploads/images')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->hideOnIndex(),

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
}
