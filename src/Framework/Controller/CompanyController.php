<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Company\Command\CreateCompanyCommand;
use App\Application\Company\CommandHandler\CreateCompanyCommandHandler;
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
            $this->redirectToRoute('company.index');
        }

        return $this->render('company/create.html.twig', ['form' => $form]);
    }
}
