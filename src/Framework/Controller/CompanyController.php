<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Common\Exception\CommandHandlerException;
use App\Application\Company\Command\CreateCompanyCommand;
use App\Application\Company\Command\UpdateCompanyCommand;
use App\Application\Company\CommandHandler\CreateCompanyCommandHandler;
use App\Application\Company\CommandHandler\UpdateCompanyCommandHandler;
use App\Application\Company\CompanyFinderException;
use App\Application\Company\CompanyFinderInterface;
use App\Domain\Company\ValueObject\CompanyId;
use App\Framework\Form\Company\CreateCompanyFormType;
use App\Framework\Form\Company\UpdateCompanyFormType;
use InvalidArgumentException;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateCompanyCommandHandler $handler,
    ): Response {
        return $this->handleForm(
            $handler,
            new CreateCompanyCommand(),
            CreateCompanyFormType::class,
            $this->generateUrl('company.index'),
            'company/create.html.twig'
        );
    }

    /**
     * @throws CommandHandlerException
     * @throws CompanyFinderException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateCompanyCommandHandler $handler,
        CompanyFinderInterface $finder,
        string $id,
    ): Response {
        $id = CompanyId::fromString($id);
        $company = $finder->findById($id);

        return $this->handleForm(
            $handler,
            UpdateCompanyCommand::fromModel($company),
            UpdateCompanyFormType::class,
            $this->generateUrl('company.index'),
            'company/update.html.twig'
        );
    }
}
