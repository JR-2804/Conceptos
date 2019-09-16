<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('hours', HiddenType::class)
            ->add('phone', HiddenType::class)
            ->add('email', HiddenType::class)
            ->add('terms', HiddenType::class)
            ->add('totalWeight', HiddenType::class)
            ->add('taxTax', HiddenType::class)
            ->add('taxFurniture', HiddenType::class)
            ->add('benefit', HiddenType::class)
            ->add('ticketPrice', HiddenType::class)
            ->add('recalculatePrice', HiddenType::class)
            ->add('image', HiddenType::class)
            ->add('privacyPolicy', HiddenType::class)
        ;
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\DTO\ConfigDTO']);
    }
}
