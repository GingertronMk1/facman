<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class ApplicationTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected UrlGeneratorInterface $router;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = $this->getContainer()->get(UrlGeneratorInterface::class);
    }

    /**
     * @param string               $route    The generated route for the form
     * @param string               $formName The name of the form
     * @param array<string, mixed> $newData  The data to add to the form
     * @param array<string, mixed> $oldData  The data that should be present in the form already
     */
    protected function checkForm(
        string $route,
        string $formName,
        array $newData,
        array $oldData = []
    ): void {
        $response = $this->client->request('GET', $route);
        $this->assertResponseIsSuccessful();

        $form = $response->filterXPath("//form[@name='{$formName}']")->form();
        $originalFormValues = $form->getValues();
        foreach ($oldData as $oldDataKey => $oldDataValue) {
            $this->assertEquals($oldDataValue, $originalFormValues[$oldDataKey]);
        }
        $form->setValues($newData);
        $this->client->submit($form);
    }
}
