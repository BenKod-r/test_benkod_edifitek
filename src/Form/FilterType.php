<?php

namespace App\Form;

use App\Entity\Stack;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isAvailable', CheckboxType::class, [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-check form-switch d-flex align-items-center'
                ],
                'label_attr' => [
                    'data-on-label' => 'Oui',
                    'data-off-label' => 'Non'
                ],
                'attr' => [
                    'data-switch' => 'secondary',
                ],
                'required' => false
            ])
            ->add('stack', EntityType::class, [
                'label' => 'Spécialité',
                'class' => Stack::class,
                'required' => false,
                'multiple' => true,
                'placeholder' => 'Choisir une spécialité',
                'row_attr' => [
                    'class' => 'd-flex align-items-center'
                ],
                'label_attr' => [
                    'class' => 'm-0 me-1'
                ],
                'attr' => [
                    'class' => ''
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
