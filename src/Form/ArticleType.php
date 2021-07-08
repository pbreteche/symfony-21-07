<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Author;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'article.fields.title',
            ])
            ->add('body')
            ->add('writtenBy', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'name',
                'placeholder' => 'author.select_placeholder',
            ])
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
            'label_format' => 'article.fields.%name%'
        ])->setAllowedTypes('with_publishedAt_field', 'boolean')
        ;
    }
}
