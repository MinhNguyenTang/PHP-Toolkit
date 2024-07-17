<?php

namespace App\Controller\Admin;

use App\Entity\Mark;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MarkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mark::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            IntegerField::new('mark'),
        ];
    }
}
