<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
        function ($rolesAsArray) {
            // transform the array to a string
            return implode(', ', $rolesAsArray);
        },
        function ($rolesAsString) {
            // transform the string back to an array
            return explode(', ', $rolesAsString);
        }
    ));
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}