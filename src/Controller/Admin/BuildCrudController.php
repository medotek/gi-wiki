<?php

namespace App\Controller\Admin;

use App\Entity\Build;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BuildCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Build::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            TextEditorField::new('description'),
            AssociationField::new('gameCharacter'),
            ChoiceField::new('buildCategory')
            ->allowMultipleChoices()
            ->setChoices([
                'Community' => 'COMMUNITY',
                'Official' => 'OFFICIAL'
            ]),
            AssociationField::new('weapons'),
            AssociationField::new('sets'),
        ];
    }

}
