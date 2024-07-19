<?php

namespace App\Framework\Controller;

use App\Application\User\Command\CreateUserCommand;
use App\Application\User\CommandHandler\CreateUserCommandHandler;
use App\Framework\Form\User\CreateUserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user', name: 'user.')]
class UserController extends AbstractController
{
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
}
