<?php

namespace App\Controller\Admin;

use App\Entity\Employee;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EmployeeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Employee::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->linkToRoute('admin_new_employee', function (Employee $employee): array {
                return [
                    'uuid' => $employee->getId(),
                ];
            });
        });

        $actions->update(Crud::PAGE_INDEX, 'new', function (Action $action) {
            return $action->linkToRoute('admin_new_employee');
        })->add(Crud::PAGE_NEW, Action::INDEX)
            ->add(Crud::PAGE_DETAIL, Action::NEW);

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle(Crud::PAGE_DETAIL, '%entity_label_plural%');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('first_name'),
            TextField::new('last_name'),
            AssociationField::new('services', 'Services')
                ->onlyOnForms(),
            TextareaField::new('imageFile', 'Image')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
            CollectionField::new('services', 'Services')
                ->hideOnForm(),
            TextField::new('email'),
            TextField::new('phone'),
            ChoiceField::new('workDays')->allowMultipleChoices()->setChoices(
                array_flip(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
            )->onlyOnForms(),
        ];
    }
}
