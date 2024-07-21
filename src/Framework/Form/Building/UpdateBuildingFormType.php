<?php

declare(strict_types=1);

namespace App\Framework\Form\Building;

use Symfony\Component\Form\FormBuilderInterface;

class UpdateBuildingFormType extends CreateBuildingFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder->remove('site');
    }
}