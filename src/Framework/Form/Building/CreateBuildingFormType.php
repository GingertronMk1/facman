<?php

declare(strict_types=1);

namespace App\Framework\Form\Building;

use App\Application\Site\SiteFinderException;
use App\Application\Site\SiteFinderInterface;
use App\Framework\Form\Address\CreateAddressFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateBuildingFormType extends AbstractType
{
    public function __construct(
        private readonly SiteFinderInterface $siteFinder
    ) {}

    /**
     * @throws SiteFinderException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('site', ChoiceType::class, [
                'choices' => $this->siteFinder->all(),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('description', TextareaType::class)
            ->add('address', CreateAddressFormType::class)
            ->add('submit', SubmitType::class)
        ;
    }
}
