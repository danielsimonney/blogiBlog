<?php

namespace App\Form;

use App\Entity\BlogPost;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddBlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('banner')
            ->add('content')
            ->add('categories', EntityType::class,  [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '',
                'empty_data' => null,
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'by_reference' => false
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
