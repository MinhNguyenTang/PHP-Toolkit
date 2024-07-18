<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContactCrudController extends AbstractCrudController
{
    use Trait\ReadOnlyTrait;
    
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function ConfigureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Messages')
            ->setPaginatorPageSize(9);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('fullName')
                ->setFormTypeOption('disabled', 'disabled'),
            EmailField::new('email')
                ->setFormTypeOption('disabled', 'disabled'),
            TextEditorField::new('Message'),
            DateTimeField::new('createdAt')
                ->hideOnForm(),
        ];
    }
}
