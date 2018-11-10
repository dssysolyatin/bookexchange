<?php

namespace App\Form\Type\Book;

use App\DTO\Request\InsertBookCategoryDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InsertBookCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bookId', TextType::class, [
            ])
            ->add('categoryId', IntegerType::class, [
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', InsertBookCategoryDTO::class);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
