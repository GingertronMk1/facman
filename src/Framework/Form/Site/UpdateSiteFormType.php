<?php

declare(strict_types=1);

namespace App\Framework\Form\Site;

use App\Application\Company\CompanyFinderException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateSiteFormType extends AbstractType
{
    /**
     * @throws CompanyFinderException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('company', TextType::class, [
                'data' => $options['data']->company->name,
                'disabled' => true,
            ])
            ->add('description', TextareaType::class)
            ->add('submit', SubmitType::class)
        ;
    }
}
