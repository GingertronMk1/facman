<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Common\Exception\CommandHandlerException;
use App\Application\Site\Command\CreateSiteCommand;
use App\Application\Site\Command\UpdateSiteCommand;
use App\Application\Site\CommandHandler\CreateSiteCommandHandler;
use App\Application\Site\CommandHandler\UpdateSiteCommandHandler;
use App\Application\Site\SiteFinderException;
use App\Application\Site\SiteFinderInterface;
use App\Domain\Site\ValueObject\SiteId;
use App\Framework\Form\Site\CreateSiteFormType;
use App\Framework\Form\Site\UpdateSiteFormType;
use InvalidArgumentException;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/site', name: 'site.')]
class SiteController extends AbstractController
{
    /**
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateSiteCommandHandler $handler,
    ): Response {
        return $this->handleForm(
            handler: $handler,
            command: new CreateSiteCommand(),
            formClass: CreateSiteFormType::class,
            redirectUrl: $this->generateUrl('site.index'),
            template: 'site/create.html.twig'
        );
    }

    /**
     * @throws SiteFinderException
     */
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(
        SiteFinderInterface $finder
    ): Response {
        return $this->render(
            'site/index.html.twig',
            [
                'sites' => $finder->all(),
            ]
        );
    }

    /**
     * @throws CommandHandlerException
     * @throws SiteFinderException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateSiteCommandHandler $handler,
        SiteFinderInterface $finder,
        string $id,
    ): Response {
        $id = SiteId::fromString($id);
        $site = $finder->findById($id);

        return $this->handleForm(
            handler: $handler,
            command: UpdateSiteCommand::fromModel($site),
            formClass: UpdateSiteFormType::class,
            redirectUrl: $this->generateUrl('site.index'),
            template: 'site/update.html.twig'
        );
    }
}
