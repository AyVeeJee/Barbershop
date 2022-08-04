<?php

namespace App\Controller\Admin;

use App\Entity\Booking;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Routing\Annotation\Route;

class BookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->update(Crud::PAGE_INDEX, 'new', function (Action $action) {
            return $action->linkToRoute('admin_booking');
        })->add(Crud::PAGE_NEW, Action::INDEX)
            ->add(Crud::PAGE_DETAIL, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('employee.first_name', 'First Name'),
            TextField::new('employee.last_name', 'Last Name'),
            TextField::new('service'),
            DateTimeField::new('begin_at'),
        ];
    }
}
