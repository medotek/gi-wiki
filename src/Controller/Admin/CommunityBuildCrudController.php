<?php

namespace App\Controller\Admin;

use App\Entity\CommunityBuild;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommunityBuildCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommunityBuild::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
