<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LieuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lieu::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Lieu')
            ->setEntityLabelInPlural('Lieux')
            // setting the name of the page
            ->setPageTitle("index", "Jte dis quoi - Administration des lieux")
            ->setPaginatorPageSize(20);
            
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('nomLieu'),
            TextField::new('typeLieu'),
            TextareaField::new('description'),
            TextField::new('numeroTelLieu'),
            TextField::new('emailLieu'),
            TextField::new('urlLieu'),
            DateTimeField::new('createdAt')
                ->hideOnForm(),
           
        ];
    }
}
