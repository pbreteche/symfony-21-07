<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('body')
        ;
        if ($options['with_publishedAt_field']) {
            $builder
                ->add('publishedAt', DateTimeType::class, [
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'input'  => 'datetime_immutable',
                    'required' => false,
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'with_publishedAt_field' => true,
        ])->setAllowedTypes('with_publishedAt_field', 'boolean')
        ;
    }
}
