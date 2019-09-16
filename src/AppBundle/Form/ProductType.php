<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', HiddenType::class)
            ->add('code', HiddenType::class)
            ->add('item', HiddenType::class)
            ->add('description', HiddenType::class)
            ->add('isFurniture', HiddenType::class)
            ->add('category', HiddenType::class)
            ->add('image', HiddenType::class)
            ->add('price', HiddenType::class)
            ->add('popular', HiddenType::class)
            ->add('recent', HiddenType::class)
            ->add('imagesToDelete', HiddenType::class)
            ->add('color', HiddenType::class)
            ->add('material', HiddenType::class)
            ->add('inStore', HiddenType::class)
            ->add('countStore', HiddenType::class)
            ->add('favoritesCategories', HiddenType::class)
            ->add('weight', HiddenType::class)
            ->add('shippingLimit', HiddenType::class)
            ->add('ikeaPrice', HiddenType::class)
            ->add('calculatePrice', HiddenType::class)
            ->add('images', HiddenType::class)
            ->add('isHighlight', HiddenType::class)
            ->add('highlightImages', HiddenType::class)
            ->add('isAriplaneForniture', HiddenType::class)
            ->add('isFragile', HiddenType::class)
            ->add('isOversize', HiddenType::class)
            ->add('isTableware', HiddenType::class)
            ->add('isLamp', HiddenType::class)
        ;
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\DTO\ProductDTO']);
    }
}
