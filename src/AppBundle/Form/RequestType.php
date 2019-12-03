<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', HiddenType::class)
            ->add('client', HiddenType::class)
            ->add('requestProducts', HiddenType::class)
            ->add('requestCards', HiddenType::class)
            ->add('finalPrice', HiddenType::class)
            ->add('transportCost', HiddenType::class)
            ->add('discount', HiddenType::class)
            ->add('firstClientDiscount', HiddenType::class)
            ->add('preFactures', HiddenType::class)
            ->add('factures', HiddenType::class)
            ->add('twoStepExtra', HiddenType::class)
            ->add('cucExtra', HiddenType::class)
        ;
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\DTO\RequestDTO']);
    }
}
