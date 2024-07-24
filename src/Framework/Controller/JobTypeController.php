<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Common\Exception\CommandHandlerException;
use App\Application\JobType\Command\CreateJobTypeCommand;
use App\Application\JobType\Command\UpdateJobTypeCommand;
use App\Application\JobType\CommandHandler\CreateJobTypeCommandHandler;
use App\Application\JobType\CommandHandler\UpdateJobTypeCommandHandler;
use App\Application\JobType\JobTypeFinderInterface;
use App\Domain\JobType\ValueObject\JobTypeId;
use App\Framework\Form\JobType\CreateJobTypeFormType;
use App\Framework\Form\JobType\UpdateJobTypeFormType;
use InvalidArgumentException;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/job-type', name: 'job-type.')]
class JobTypeController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(JobTypeFinderInterface $jobTypeFinder): Response
    {
        return $this->render('job-type/index.html.twig', ['types' => $jobTypeFinder->all()]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws CommandHandlerException
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateJobTypeCommandHandler $handler,
    ): Response {
        return $this->handleForm(
            handler: $handler,
            command: new CreateJobTypeCommand(),
            formClass: CreateJobTypeFormType::class,
            redirectUrl: $this->generateUrl('job-type.index'),
            template: 'job-type/create.html.twig'
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws CommandHandlerException
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    #[Route(path: '/{id}/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateJobTypeCommandHandler $handler,
        JobTypeFinderInterface $finder,
        string $id,
    ): Response {
        $id = JobTypeId::fromString($id);
        $jobType = $finder->findById($id);

        return $this->handleForm(
            handler: $handler,
            command: UpdateJobTypeCommand::fromModel($jobType),
            formClass: UpdateJobTypeFormType::class,
            redirectUrl: $this->generateUrl('job-type.index'),
            template: 'job-type/update.html.twig'
        );
    }
}
