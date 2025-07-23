<?php

namespace App\Controller\Admin;

use App\Entity\Bouteille;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BouteilleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bouteille::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
        TextField::new('name', 'Nom'),
        IntegerField::new('ancien', 'Millésime'),
        IntegerField::new('degres', 'Degrés'),
        TextField::new('gout', 'Goût'),
        IntegerField::new('public', 'Public (0 = non publique & 1 = publique)'),
        TextField::new('grapes', 'Cépages'),

        ];
    }

    public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Bouteille) {
            // Supprime les BouteilleView liés à cette bouteille
            foreach ($entityInstance->getBouteilleViews() as $bv) {
                $em->remove($bv);
            }
            // Supprime les CaveBouteille liés à cette bouteille
            foreach ($entityInstance->getCaveBouteilles() as $cb) {
                $em->remove($cb);
            }
        }
        parent::deleteEntity($em, $entityInstance);
    }
    
}
