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
use App\Domain\Building\BuildingRepositoryException;
use App\Domain\Building\ValueObject\BuildingId;
use App\Domain\Common\Exception\AbstractRepositoryException;
use App\Framework\Form\Building\CreateBuildingFormType;
use App\Framework\Form\Building\UpdateBuildingFormType;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/building', name: 'building.')]
class BuildingController extends AbstractController
{
    /**
     * @throws LogicException
     * @throws BuildingRepositoryException
     * @throws AbstractRepositoryException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateBuildingCommandHandler $handler,
        Request $request
    ): Response {
        $command = new CreateBuildingCommand();
        $form = $this->createForm(CreateBuildingFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('building.index');
        }

        return $this->render('building/create.html.twig', ['form' => $form]);
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
     * @throws LogicException
     * @throws BuildingRepositoryException
     * @throws BuildingFinderException
     * @throws AbstractFinderException
     * @throws AbstractRepositoryException
     */
    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateBuildingCommandHandler $handler,
        BuildingFinderInterface $finder,
        string $id,
        Request $request
    ): Response {
        $id = BuildingId::fromString($id);
        $building = $finder->findById($id);
        $command = UpdateBuildingCommand::fromModel($building);
        $form = $this->createForm(UpdateBuildingFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('building.index');
        }

        return $this->render(
            'building/update.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
