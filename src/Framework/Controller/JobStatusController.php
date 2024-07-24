<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Common\Exception\CommandHandlerException;
use App\Application\JobStatus\Command\CreateJobStatusCommand;
use App\Application\JobStatus\Command\UpdateJobStatusCommand;
use App\Application\JobStatus\CommandHandler\CreateJobStatusCommandHandler;
use App\Application\JobStatus\CommandHandler\UpdateJobStatusCommandHandler;
use App\Application\JobStatus\JobStatusFinderException;
use App\Application\JobStatus\JobStatusFinderInterface;
use App\Domain\JobStatus\ValueObject\JobStatusId;
use App\Framework\Form\JobStatus\CreateJobStatusFormType;
use App\Framework\Form\JobStatus\UpdateJobStatusFormType;
use InvalidArgumentException;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/job-status', name: 'job-status.')]
class JobStatusController extends AbstractController
{
    /**
     * @throws JobStatusFinderException
     */
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(
        JobStatusFinderInterface $finder
    ): Response {
        return $this->render(
            'job-status/index.html.twig',
            [
                'statuses' => $finder->all(),
            ]
        );
    }

    /**
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateJobStatusCommandHandler $handler,
    ): Response {
        return $this->handleForm(
            handler: $handler,
            command: new CreateJobStatusCommand(),
            formClass: CreateJobStatusFormType::class,
            redirectUrl: $this->generateUrl('job-status.index'),
            template: 'job-status/create.html.twig'
        );
    }

    /**
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateJobStatusCommandHandler $handler,
        JobStatusFinderInterface $finder,
        string $id,
    ): Response {
        $id = JobStatusId::fromString($id);
        $jobStatus = $finder->findById($id);

        return $this->handleForm(
            handler: $handler,
            command: UpdateJobStatusCommand::fromModel($jobStatus),
            formClass: UpdateJobStatusFormType::class,
            redirectUrl: $this->generateUrl('job-status.index'),
            template: 'job-status/update.html.twig'
        );
    }
}
