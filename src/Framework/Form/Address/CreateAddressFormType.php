<?php

declare(strict_types=1);

namespace App\Framework\Form\Address;

use App\Application\Address\Command\StoreAddressCommand;
use App\Domain\Address\AddressTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateAddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addressType', ChoiceType::class, [
                'choices' => AddressTypeEnum::cases(),
                'choice_label' => fn (?AddressTypeEnum $type) => ucfirst($type?->value ?? 'None'),
                'choice_value' => fn (?AddressTypeEnum $type) => $type?->value ?? 'None',
            ])
            ->add('line1', TextType::class, ['required' => false])
            ->add('line2', TextType::class, ['required' => false])
            ->add('line3', TextType::class, ['required' => false])
            ->add('postcode', TextType::class, ['required' => false])
            ->add('city', TextType::class, ['required' => false])
            ->add('country', TextType::class, ['required' => false])
        ;
    }

    /**
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StoreAddressCommand::class,
        ]);
    }
}
