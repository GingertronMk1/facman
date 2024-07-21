<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Company\Command\CreateCompanyCommand;
use App\Application\Company\CommandHandler\CreateCompanyCommandHandler;
use App\Application\Company\CompanyFinderException;
use App\Application\Company\CompanyFinderInterface;
use App\Domain\Company\CompanyRepositoryException;
use App\Framework\Form\Company\CreateCompanyFormType;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/company', name: 'company.')]
class CompanyController extends AbstractController
{
    /**
     * @throws CompanyFinderException
     */
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(
        CompanyFinderInterface $finder
    ): Response {
        return $this->render(
            'company/index.html.twig',
            [
                'companies' => $finder->all(),
            ]
        );
    }

    /**
     * @throws LogicException
     * @throws CompanyRepositoryException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateCompanyCommandHandler $handler,
        Request $request
    ): Response {
        $command = new CreateCompanyCommand();
        $form = $this->createForm(CreateCompanyFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('company.index');
        }

        return $this->render(
            'company/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
