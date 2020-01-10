<?php

namespace AppBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), [
            'label' => 'form.email',
            'translation_domain' => 'FOSUserBundle',
            'attr' => ['placeholder' => 'Correo'],
        ]);
        $builder->add('username', null, [
            'label' => 'form.username',
            'translation_domain' => 'FOSUserBundle',
            'attr' => ['placeholder' => 'Usuario'],
        ]);
        $builder->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), [
            'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
            'options' => ['translation_domain' => 'FOSUserBundle'],
            'first_options' => ['label' => 'form.password', 'attr' => ['class' => 'd-flex w-100', 'placeholder' => 'Contraseña']],
            'second_options' => ['label' => 'form.password_confirmation', 'attr' => ['class' => 'd-flex w-100', 'placeholder' => 'Confirme la contraseña']],
            'invalid_message' => 'fos_user.password.mismatch',
        ]);
        $builder->add('first_name', TextType::class, ['label' => 'Nombre']);
        $builder->add('last_name', TextType::class, ['label' => 'Apellidos']);
        $builder->add('mobile_number', TextType::class, ['label' => 'Teléfono Movil']);
        $builder->add('home_number', TextType::class, ['label' => 'Teléfono de Casa']);
        $builder->add('address', TextType::class, ['label' => 'Dirección']);
        $builder->add('birth_date', BirthdayType::class, ['label' => 'Fecha de Nacimiento']);
        $builder->add('image', HiddenType::class);
        $builder->add('jsonImage', HiddenType::class);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
