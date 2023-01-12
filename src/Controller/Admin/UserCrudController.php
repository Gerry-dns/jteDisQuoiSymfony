<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    // this function is mandatory
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // this function configure the crud (design, titles, entities...)
    public function configureCrud(Crud $crud): Crud 
    {
        return $crud
        // changing the name of the Entity (User) in French 
            ->setEntityLabelInPlural('Utilisateurs')
        // when clicking on one single User, the name will be "Utilisateur"
            ->setEntityLabelInSingular('Utilisateur')

            ->setPageTitle("index", "Jte dis quoi - Administration des utilisateurs")
            ->setPaginatorPageSize(10);
    }
    // display the differents fields
    public function configureFields(string $pageName): iterable
    {
        return [
            // on the dashboard, there will be displayed : 
            // id is the name of the property of the Entity
            IdField::new('id')
                //id won't be display when clicked on editing option
                ->hideOnForm(),
            TextField::new('fullName'),
            TextField::new('pseudo'),
            TextField::new('email')
                // email will be displayed, but cannot be changed
                ->setFormTypeOption('disabled', 'disabled'),
            ArrayField::new('roles'),
            DateTimeField::new('createdAt')
                ->hideOnForm()
        ];
    }
    
}
