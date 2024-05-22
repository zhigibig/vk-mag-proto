<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Subcategories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('image_url')
            ->add('price')
            ->add('parameters')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('subcategory', EntityType::class, [
                'class' => Subcategories::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
