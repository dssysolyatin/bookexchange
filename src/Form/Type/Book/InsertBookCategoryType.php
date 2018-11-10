<?php


namespace App\Form\Type\Book;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class InsertBookCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('book_id', TextType::class, [
                'property_path' => 'bookId'
            ])
            ->add('category_id', IntegerType::class, [
                'property_path' => 'categoryId'
            ])
        ;
    }

}