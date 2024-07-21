<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Site\Command\CreateSiteCommand;
use App\Application\Site\Command\UpdateSiteCommand;
use App\Application\Site\CommandHandler\CreateSiteCommandHandler;
use App\Application\Site\CommandHandler\UpdateSiteCommandHandler;
use App\Application\Site\SiteFinderException;
use App\Application\Site\SiteFinderInterface;
use App\Domain\Site\SiteRepositoryException;
use App\Domain\Site\ValueObject\SiteId;
use App\Framework\Form\Site\CreateSiteFormType;
use App\Framework\Form\Site\UpdateSiteFormType;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/site', name: 'site.')]
class SiteController extends AbstractController
{
    /**
     * @throws LogicException
     * @throws SiteRepositoryException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateSiteCommandHandler $handler,
        Request $request
    ): Response {
        $command = new CreateSiteCommand();
        $form = $this->createForm(CreateSiteFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('site.index');
        }

        return $this->render('site/create.html.twig', ['form' => $form]);
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
     * @throws LogicException
     * @throws SiteRepositoryException
     * @throws SiteFinderException
     */
    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateSiteCommandHandler $handler,
        SiteFinderInterface $finder,
        string $id,
        Request $request
    ): Response {
        $id = SiteId::fromString($id);
        $site = $finder->findById($id);
        $command = UpdateSiteCommand::fromModel($site);
        $form = $this->createForm(UpdateSiteFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('site.index');
        }

        return $this->render(
            'site/update.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
