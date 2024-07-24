<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Common\Exception\AbstractFinderException;
use App\Application\Common\Exception\CommandHandlerException;
use App\Application\Floor\Command\CreateFloorCommand;
use App\Application\Floor\Command\UpdateFloorCommand;
use App\Application\Floor\CommandHandler\CreateFloorCommandHandler;
use App\Application\Floor\CommandHandler\UpdateFloorCommandHandler;
use App\Application\Floor\FloorFinderException;
use App\Application\Floor\FloorFinderInterface;
use App\Domain\Floor\ValueObject\FloorId;
use App\Framework\Form\Floor\CreateFloorFormType;
use App\Framework\Form\Floor\UpdateFloorFormType;
use InvalidArgumentException;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/floor', name: 'floor.')]
class FloorController extends AbstractController
{
    /**
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateFloorCommandHandler $handler,
    ): Response {
        return $this->handleForm(
            handler: $handler,
            command: new CreateFloorCommand(),
            formClass: CreateFloorFormType::class,
            redirectUrl: $this->generateUrl('floor.index'),
            template: 'floor/create.html.twig'
        );
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
     * @throws AbstractFinderException
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateFloorCommandHandler $handler,
        FloorFinderInterface $finder,
        string $id,
    ): Response {
        $id = FloorId::fromString($id);
        $floor = $finder->findById($id);

        return $this->handleForm(
            handler: $handler,
            command: UpdateFloorCommand::fromModel($floor),
            formClass: UpdateFloorFormType::class,
            redirectUrl: $this->generateUrl('floor.index'),
            template: 'floor/update.html.twig'
        );
    }
}
