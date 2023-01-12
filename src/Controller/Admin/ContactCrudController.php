<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Demande de contact')
            ->setEntityLabelInPlural('Demandes de contact')
            // setting the name of the page
            ->setPageTitle("index", "Jte dis quoi - Administration des demandes de contact")
            ->setPaginatorPageSize(20)
            // adding WYSIWYG 
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('fullName')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('email')
                ->setFormTypeOption('disabled', 'disabled'),
            TextareaField::new('message')
                ->setFormType(CKEditorType::class)
                ->hideOnIndex(),
            DateTimeField::new('createdAt')
                ->hideOnForm()
        ];
    }
    
}
