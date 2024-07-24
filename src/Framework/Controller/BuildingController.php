<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Building\BuildingFinderException;
use App\Application\Building\BuildingFinderInterface;
use App\Application\Building\Command\CreateBuildingCommand;
use App\Application\Building\Command\UpdateBuildingCommand;
use App\Application\Building\CommandHandler\CreateBuildingCommandHandler;
use App\Application\Building\CommandHandler\UpdateBuildingCommandHandler;
use App\Application\Common\Exception\AbstractFinderException;
use App\Application\Common\Exception\CommandHandlerException;
use App\Domain\Building\ValueObject\BuildingId;
use App\Framework\Form\Building\CreateBuildingFormType;
use App\Framework\Form\Building\UpdateBuildingFormType;
use InvalidArgumentException;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/building', name: 'building.')]
class BuildingController extends AbstractController
{
    /**
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateBuildingCommandHandler $handler,
    ): Response {
        return $this->handleForm(
            handler: $handler,
            command: new CreateBuildingCommand(),
            formClass: CreateBuildingFormType::class,
            redirectUrl: $this->generateUrl('building.index'),
            template: 'building/create.html.twig'
        );
    }

    /**
     * @throws BuildingFinderException
     * @throws AbstractFinderException
     */
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(
        BuildingFinderInterface $finder
    ): Response {
        return $this->render(
            'building/index.html.twig',
            [
                'buildings' => $finder->all(),
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
        UpdateBuildingCommandHandler $handler,
        BuildingFinderInterface $finder,
        string $id,
    ): Response {
        $id = BuildingId::fromString($id);
        $building = $finder->findById($id);

        return $this->handleForm(
            handler: $handler,
            command: UpdateBuildingCommand::fromModel($building),
            formClass: UpdateBuildingFormType::class,
            redirectUrl: $this->generateUrl('building.index'),
            template: 'building/update.html.twig'
        );
    }
}
