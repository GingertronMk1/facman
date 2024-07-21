<?php

namespace App\Framework\Provider;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class NavProvider
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    /**
     * @return array<string, string>
     *
     * @throws InvalidParameterException
     * @throws MissingMandatoryParametersException
     * @throws RouteNotFoundException
     */
    public function links(): array
    {
        return [
            'Companies' => $this->urlGenerator->generate('company.index'),
            'Sites' => $this->urlGenerator->generate('site.index'),
            'Buildings' => $this->urlGenerator->generate('building.index'),
        ];
    }
}
