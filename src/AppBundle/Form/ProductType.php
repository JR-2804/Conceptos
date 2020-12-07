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
            ->add('isMattress', HiddenType::class)
            ->add('category', HiddenType::class)
            ->add('image', HiddenType::class)
            ->add('price', HiddenType::class)
            ->add('popular', HiddenType::class)
            ->add('priority', HiddenType::class)
            ->add('recent', HiddenType::class)
            ->add('imagesToDelete', HiddenType::class)
            ->add('color', HiddenType::class)
            ->add('material', HiddenType::class)
            ->add('inStore', HiddenType::class)
            ->add('countStore', HiddenType::class)
            ->add('favoritesCategories', HiddenType::class)
            ->add('comboProducts', HiddenType::class)
            ->add('similarProducts', HiddenType::class)
            ->add('complementaryProducts', HiddenType::class)
            ->add('weight', HiddenType::class)
            ->add('shippingLimit', HiddenType::class)
            ->add('ikeaPrice', HiddenType::class)
            ->add('calculatePrice', HiddenType::class)
            ->add('images', HiddenType::class)
            ->add('isHighlight', HiddenType::class)
            ->add('highlightImages', HiddenType::class)
            ->add('isAriplaneForniture', HiddenType::class)
            ->add('isAriplaneMattress', HiddenType::class)
            ->add('isFragile', HiddenType::class)
            ->add('isOversize', HiddenType::class)
            ->add('isTableware', HiddenType::class)
            ->add('isLamp', HiddenType::class)
            ->add('isFaucet', HiddenType::class)
            ->add('isGrill', HiddenType::class)
            ->add('isShelf', HiddenType::class)
            ->add('isDesk', HiddenType::class)
            ->add('isBookcase', HiddenType::class)
            ->add('isComoda', HiddenType::class)
            ->add('isRepisa', HiddenType::class)
            ->add('numberOfPackages', HiddenType::class)
            ->add('isDisabled', HiddenType::class)
            ->add('metaNames', HiddenType::class)
            ->add('widthLeft', HiddenType::class)
            ->add('widthRight', HiddenType::class)
            ->add('width', HiddenType::class)
            ->add('heightMin', HiddenType::class)
            ->add('heightMax', HiddenType::class)
            ->add('height', HiddenType::class)
            ->add('deepMin', HiddenType::class)
            ->add('deepMax', HiddenType::class)
            ->add('deep', HiddenType::class)
            ->add('length', HiddenType::class)
            ->add('diameter', HiddenType::class)
            ->add('maxLoad', HiddenType::class)
            ->add('area', HiddenType::class)
            ->add('thickness', HiddenType::class)
            ->add('volume', HiddenType::class)
            ->add('surfaceDensity', HiddenType::class)
            ->add('codes', HiddenType::class)
            ->add('colors', HiddenType::class)
            ->add('materials', HiddenType::class)
            ->add('classification', HiddenType::class)
            ->add('rooms', HiddenType::class)
        ;
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\DTO\ProductDTO']);
    }
}
