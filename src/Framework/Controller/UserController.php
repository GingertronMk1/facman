<?php

namespace App\Framework\Controller;

use App\Application\Common\Exception\CommandHandlerException;
use App\Application\User\Command\CreateUserCommand;
use App\Application\User\Command\UpdateUserCommand;
use App\Application\User\CommandHandler\CreateUserCommandHandler;
use App\Application\User\CommandHandler\UpdateUserCommandHandler;
use App\Application\User\UserModel;
use App\Framework\Form\User\CreateUserFormType;
use App\Framework\Form\User\UpdateUserFormType;
use Exception;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user', name: 'user.')]
class UserController extends AbstractController
{
    /**
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateUserCommandHandler $handler,
    ): Response {
        return $this->handleForm(
            handler: $handler,
            command: new CreateUserCommand(),
            formClass: CreateUserFormType::class,
            redirectUrl: $this->generateUrl('app_login'),
            template: 'user/create.html.twig'
        );
    }

    /**
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws LogicException
     */
    #[Route(path: '/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateUserCommandHandler $handler,
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof UserModel) {
            throw new Exception();
        }

        return $this->handleForm(
            handler: $handler,
            command: UpdateUserCommand::fromModel($user),
            formClass: UpdateUserFormType::class,
            redirectUrl: $this->generateUrl('index'),
            template: 'user/update.html.twig'
        );
    }
}
