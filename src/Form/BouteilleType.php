<?php

namespace App\Form;

use App\Entity\Cave;
use App\Entity\User;
use App\Entity\Color;
use App\Entity\Region;
use App\Entity\Bouteille;
use App\Entity\Producteur;
use App\Entity\Appellation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BouteilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('ancien', TextType::class, [
                'label' => 'Ancien',
                'required' => false,
            ])
            ->add('degres', TextType::class, [
                'label' => 'Degrés',
                'required' => false,
            ])
            ->add('quantite')
            ->add('purchasePrice')
            ->add('grapes', TextType::class, [
                'label' => 'Cépages',
                'required' => false,
            ])
            ->add('Gout')
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label' => 'Image de la bouteille',
                'allow_delete' => false,
                'download_uri' => false,
            ])
            ->add('color', EntityType::class, [
                'class' => Color::class,
                'choice_label' => 'name',
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bouteille::class,
        ]);
    }
}
