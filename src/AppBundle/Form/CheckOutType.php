<?php

namespace AppBundle\Form;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckOutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('address', TextType::class)
            ->add('phone', TextType::class)
            ->add('movil', TextType::class)
            ->add('products', HiddenType::class)
            ->add('memberNumber', HiddenType::class, [
                'required' => false,
            ])
            ->add('recaptcha', EWZRecaptchaType::class, ['language' => 'es'])
        ;
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\DTO\CheckOutDTO']);
    }
}
