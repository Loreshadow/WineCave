<?php

namespace App\Form;

use App\Entity\Appellation;
use App\Entity\Bouteille;
use App\Entity\Cave;
use App\Entity\Color;
use App\Entity\Producteur;
use App\Entity\Region;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BouteilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('ancien')
            ->add('degres')
            ->add('quantite')
            ->add('purchasePrice')
            ->add('Gout')
            ->add('image')
            ->add('color', EntityType::class, [
                'class' => Color::class,
                'choice_label' => 'name',
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
            ])
            ->add('appellation', EntityType::class, [
                'class' => Appellation::class,
                'choice_label' => 'id',
            ])
            ->add('producteur', EntityType::class, [
                'class' => Producteur::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bouteille::class,
        ]);
    }
}
