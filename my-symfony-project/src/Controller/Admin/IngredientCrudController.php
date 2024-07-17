<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IngredientCrudController extends AbstractCrudController
{
    use Trait\ReadOnlyTrait;
    
    public static function getEntityFqcn(): string
    {
        return Ingredient::class;
    }

    public function ConfigureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Ingredients')
            ->setPaginatorPageSize(9);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name'),
            MoneyField::new('price')->setCurrency('EUR'),
            DateTimeField::new('createdAt')
            ->hideOnForm(),
        ];
    }
}
