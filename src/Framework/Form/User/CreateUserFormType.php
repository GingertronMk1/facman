<?php

namespace App\Framework\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add(
                'password', RepeatedType::class,
                [
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                    'type' => PasswordType::class,
                ]
            )
            ->add('submit', SubmitType::class)
        ;
    }
}
