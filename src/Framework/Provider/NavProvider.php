<?php

namespace App\Framework\Provider;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class NavProvider
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function links(): array
    {
        return [
            'Companies' => $this->urlGenerator->generate('company.index'),
            'Sites' => $this->urlGenerator->generate('site.index'),
        ];
    }
}