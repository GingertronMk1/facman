<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Common\Exception\AbstractFinderException;
use App\Application\Floor\Command\CreateFloorCommand;
use App\Application\Floor\Command\UpdateFloorCommand;
use App\Application\Floor\CommandHandler\CreateFloorCommandHandler;
use App\Application\Floor\CommandHandler\UpdateFloorCommandHandler;
use App\Application\Floor\FloorFinderException;
use App\Application\Floor\FloorFinderInterface;
use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Domain\Floor\FloorRepositoryException;
use App\Domain\Floor\ValueObject\FloorId;
use App\Framework\Form\Floor\CreateFloorFormType;
use App\Framework\Form\Floor\UpdateFloorFormType;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/floor', name: 'floor.')]
class FloorController extends AbstractController
{
    /**
     * @throws LogicException
     * @throws FloorRepositoryException
     * @throws AbstractRepositoryException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateFloorCommandHandler $handler,
        Request $request
    ): Response {
        $command = new CreateFloorCommand();
        $form = $this->createForm(CreateFloorFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('floor.index');
        }

        return $this->render('floor/create.html.twig', ['form' => $form]);
    }

    /**
     * @throws FloorFinderException
     * @throws AbstractFinderException
     */
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(
        FloorFinderInterface $finder
    ): Response {
        return $this->render(
            'floor/index.html.twig',
            [
                'floors' => $finder->all(),
            ]
        );
    }

    /**
     * @throws LogicException
     * @throws FloorRepositoryException
     * @throws FloorFinderException
     * @throws AbstractFinderException
     * @throws AbstractRepositoryException
     */
    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateFloorCommandHandler $handler,
        FloorFinderInterface $finder,
        string $id,
        Request $request
    ): Response {
        $id = FloorId::fromString($id);
        $floor = $finder->findById($id);
        $command = UpdateFloorCommand::fromModel($floor);
        $form = $this->createForm(UpdateFloorFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('floor.index');
        }

        return $this->render(
            'floor/update.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
