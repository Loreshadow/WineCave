<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('firstName');
        yield TextField::new('lastName');
        yield EmailField::new('email');
        yield TextField::new('username');
    }
    public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $cave = $entityInstance->getCave();
            if ($cave) {
                // Supprime les CaveBouteille liés à la cave
                foreach ($cave->getCaveBouteilles() as $cb) {
                    $em->remove($cb);
                }
                $em->remove($cave);
            }
        }
        parent::deleteEntity($em, $entityInstance);
    }
}