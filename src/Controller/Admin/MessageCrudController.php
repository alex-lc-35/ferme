<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Enum\MessageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    BooleanField,
    ChoiceField,
    IdField,
    TextareaField,
    TextField,
    AssociationField
};
use Symfony\Bundle\SecurityBundle\Security;

class MessageCrudController extends AbstractCrudController
{
    public function __construct(private Security $security) {}

    public static function getEntityFqcn(): string
    {
        return Message::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];


        $fields[] = ChoiceField::new('type')
            ->setLabel('Type de message')
            ->renderAsBadges()
            ->formatValue(fn($value) => match($value) {
                MessageType::MARQUEE => ' Bannière',
                MessageType::CLOSEDSHOP => 'Fermeture boutique',
                default => $value,
            });

        if ($pageName === 'new') {
            $fields[] = ChoiceField::new('type')
                ->setLabel('Type de message')
                ->setChoices([
                    'Message bannière (déroulant)' => MessageType::MARQUEE,
                    'Fermeture boutique' => MessageType::CLOSEDSHOP,
                ])
                ->renderExpanded();
        }

        $fields[] = TextareaField::new('content')
            ->setLabel('Contenu')
            ->setFormTypeOption('attr', ['class' => 'wysiwyg'])
            ->renderAsHtml();

        $fields[] = BooleanField::new('isActive')->setLabel('Afficher aux clients');

        $fields[] = AssociationField::new('user')->hideOnForm();

        return $fields;
    }

    public function createEntity(string $entityFqcn): Message
    {
        $message = new Message();
        $message->setUser($this->security->getUser());
        return $message;
    }
}
