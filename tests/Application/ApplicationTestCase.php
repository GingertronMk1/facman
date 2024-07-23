<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApplicationTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected UrlGeneratorInterface $router;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = $this->getContainer()->get(UrlGeneratorInterface::class);
    }
}