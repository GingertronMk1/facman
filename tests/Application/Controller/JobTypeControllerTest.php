<?php

namespace App\Tests\Application\Controller;

use App\Application\JobType\JobTypeFinderInterface;
use App\Application\JobType\JobTypeModel;
use App\Domain\JobType\JobTypeEntity;
use App\Domain\JobType\JobTypeRepositoryInterface;
use App\Tests\Application\ApplicationTestCase;
use LogicException;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * @internal
 */
final class JobTypeControllerTest extends ApplicationTestCase
{
    private JobTypeFinderInterface $finder;
    private JobTypeRepositoryInterface $repository;

    /**
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     * @throws LogicException
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        /** @var JobTypeFinderInterface $finder */
        $finder = self::getContainer()->get(JobTypeFinderInterface::class);
        $this->finder = $finder;

        /** @var JobTypeRepositoryInterface $repository */
        $repository = self::getContainer()->get(JobTypeRepositoryInterface::class);
        $this->repository = $repository;
    }

    /**
     * @throws InvalidParameterException
     * @throws MissingMandatoryParametersException
     * @throws RouteNotFoundException
     */
    public function testIndex(): void
    {
        $this->client->request(
            'GET',
            $this->router->generate('job-type.index')
        );

        self::assertResponseIsSuccessful();
    }

    public function testCreate(): void
    {
        $this->checkForm(
            'job-type.create',
            [],
            'create_job_type_form',
            [
                'create_job_type_form[name]' => self::class,
                'create_job_type_form[description]' => 'Test JobType',
                'create_job_type_form[colour]' => '#FF0000',
            ]
        );
        self::assertResponseRedirects();

        $allStatuses = $this->finder->all();
        self::assertContains(
            self::class,
            array_map(
                fn (JobTypeModel $jsm) => $jsm->name,
                $allStatuses
            )
        );
    }

    public function testUpdate(): void
    {
        $id = $this->repository->generateId();
        $originalName = 'Update Test JobType';
        $originalDescription = 'a description';
        $job_type = new JobTypeEntity(
            id: $id,
            name: $originalName,
            description: $originalDescription,
            colour: '#BADA55'
        );
        $this->repository->store($job_type);

        $newName = 'Update Test JobType Again';
        $newDescription = 'Changed';

        $this->checkForm(
            'job-type.update',
            ['id' => $id],
            'update_job_type_form',
            [
                'update_job_type_form[name]' => $newName,
                'update_job_type_form[description]' => $newDescription,
            ],
            [
                'update_job_type_form[name]' => $originalName,
                'update_job_type_form[description]' => $originalDescription,
            ]
        );
        self::assertResponseRedirects();

        $job_type = $this->finder->findById($id);
        self::assertEquals($newName, $job_type->name);
        self::assertEquals($newDescription, $job_type->description);
    }
}
