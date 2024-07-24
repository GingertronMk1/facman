<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\JobStatus\Command\CreateJobStatusCommand;
use App\Application\JobStatus\Command\UpdateJobStatusCommand;
use App\Application\JobStatus\CommandHandler\CreateJobStatusCommandHandler;
use App\Application\JobStatus\CommandHandler\UpdateJobStatusCommandHandler;
use App\Application\JobStatus\JobStatusFinderException;
use App\Application\JobStatus\JobStatusFinderInterface;
use App\Domain\JobStatus\JobStatusRepositoryException;
use App\Domain\JobStatus\ValueObject\JobStatusId;
use App\Framework\Form\JobStatus\CreateJobStatusFormType;
use App\Framework\Form\JobStatus\UpdateJobStatusFormType;
use InvalidArgumentException;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
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
     * @throws JobStatusRepositoryException
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateJobStatusCommandHandler $handler,
        Request $request
    ): Response {
        $command = new CreateJobStatusCommand();
        $form = $this->createForm(CreateJobStatusFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('job-status.index');
        }

        return $this->render(
            'job-status/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    /**
     * @throws JobStatusRepositoryException
     * @throws JobStatusFinderException
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateJobStatusCommandHandler $handler,
        JobStatusFinderInterface $finder,
        string $id,
        Request $request
    ): Response {
        $id = JobStatusId::fromString($id);
        $jobStatus = $finder->findById($id);
        $command = UpdateJobStatusCommand::fromModel($jobStatus);
        $form = $this->createForm(UpdateJobStatusFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('job-status.index');
        }

        return $this->render(
            'job-status/update.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
