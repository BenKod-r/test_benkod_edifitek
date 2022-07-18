<?php

namespace App\Form;

use App\Entity\Stack;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MentorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isAvailable', CheckboxType::class, [
                'label' => false,
                'label_attr' => [
                    'data-on-label' => 'Oui',
                    'data-off-label' => 'Non'
                ],
                'attr' => [
                    'data-switch' => 'bool'
                ],
                'required' => false,
            ])
            ->add('stack', EntityType::class, [
                'class' => Stack::class,
                'multiple' => true,
                'label' => 'Modifier vos spécialités',
                'attr' => [
                    'class' => 'select2 form-control select2-multiple',
                    'data-toggle' => 'select2',
                    'data-placeholder' => 'Rechercher...'
                ]
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
