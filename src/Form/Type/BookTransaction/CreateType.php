<?php

namespace App\Form\Type\BookTransaction;

use App\DTO\Request\CreateBookTransaction;
use App\Entity\Book;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('requester', EntityType::class, [
                'class' => User::class,
            ])
            ->add('keeper', EntityType::class, [
                'class' => User::class,
            ])
            ->add('book', EntityType::class, [
                'class' => Book::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', CreateBookTransaction::class);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
