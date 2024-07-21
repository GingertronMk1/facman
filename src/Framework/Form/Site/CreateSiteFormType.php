<?php

declare(strict_types=1);

namespace App\Framework\Form\Site;

use App\Application\Company\CompanyFinderException;
use App\Application\Company\CompanyFinderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateSiteFormType extends AbstractType
{
    public function __construct(
        private readonly CompanyFinderInterface $companyFinder
    ) {}

    /**
     * @throws CompanyFinderException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('company', ChoiceType::class, [
                'choices' => $this->companyFinder->all(),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('description', TextareaType::class)
            ->add('submit', SubmitType::class)
        ;
    }
}
