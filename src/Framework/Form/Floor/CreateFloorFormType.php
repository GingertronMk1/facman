<?php

declare(strict_types=1);

namespace App\Framework\Form\Floor;

use App\Application\Building\BuildingFinderInterface;
use App\Application\Common\Exception\AbstractFinderException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateFloorFormType extends AbstractType
{
    public function __construct(
        private readonly BuildingFinderInterface $buildingFinder
    ) {}

    /**
     * @throws AbstractFinderException
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('building', ChoiceType::class, [
                'choices' => $this->buildingFinder->all(),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('description', TextareaType::class)
            ->add('submit', SubmitType::class)
        ;
    }
}
