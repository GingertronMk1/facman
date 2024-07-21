<?php

declare(strict_types=1);

namespace App\Framework\Form\Site;

use App\Application\Company\CompanyFinderException;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateSiteFormType extends CreateSiteFormType
{
    /**
     * @throws CompanyFinderException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('company')
        ;
    }
}
