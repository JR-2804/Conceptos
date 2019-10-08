<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', HiddenType::class)
            ->add('client', HiddenType::class)
            ->add('factureProducts', HiddenType::class)
            ->add('factureCards', HiddenType::class)
            ->add('finalPrice', HiddenType::class)
            ->add('transportCost', HiddenType::class)
            ->add('discount', HiddenType::class)
            ->add('firstClientDiscount', HiddenType::class)
        ;
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\DTO\FactureDTO']);
    }
}