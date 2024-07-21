<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Site\Command\CreateSiteCommand;
use App\Application\Site\CommandHandler\CreateSiteCommandHandler;
use App\Domain\Site\SiteRepositoryException;
use App\Framework\Form\Site\CreateSiteFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/site', name: 'site.')]
class SiteController extends AbstractController
{
    /**
     * @throws \LogicException
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
            $this->redirectToRoute('site.index');
        }

        return $this->render('site/create.html.twig', ['form' => $form]);
    }
}
