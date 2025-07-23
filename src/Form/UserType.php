<?php

namespace App\Form;

use App\Entity\Cave;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('username')
            ->add('roles')
            ->add('password')
            ->add('role')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('pseudoColor')
            ->add('Privacy')
            ->add('cave', EntityType::class, [
                'class' => Cave::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
