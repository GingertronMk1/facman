<?php

namespace App\Framework\Controller;

use App\Application\User\Command\CreateUserCommand;
use App\Application\User\Command\UpdateUserCommand;
use App\Application\User\CommandHandler\CreateUserCommandHandler;
use App\Application\User\CommandHandler\UpdateUserCommandHandler;
use App\Application\User\UserModel;
use App\Framework\Form\User\CreateUserFormType;
use App\Framework\Form\User\UpdateUserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user', name: 'user.')]
class UserController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        CreateUserCommandHandler $handler,
        Request $request
    ): Response {
        $command = new CreateUserCommand();
        $form = $this->createForm(CreateUserFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('app_login');
            } catch (\Throwable $e) {
                throw new \Exception('Error creating person', previous: $e);
            }
        }

        return $this->render(
            'user/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(
        UpdateUserCommandHandler $handler,
        Request $request
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof UserModel) {
            throw new \Exception();
        }
        $command = UpdateUserCommand::fromModel($user);
        $form = $this->createForm(UpdateUserFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);

                return $this->redirectToRoute('index');
            } catch (\Throwable $e) {
                throw new \Exception('Error updating person', previous: $e);
            }
        }

        return $this->render(
            'user/update.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
