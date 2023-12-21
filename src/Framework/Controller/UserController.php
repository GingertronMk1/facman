<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use Exception;
use App\Application\User\CreateUserCommand;
use App\Application\User\CreateUserCommandHandler;
use App\Framework\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/user/create', 'user.create', methods: ['GET', 'POST'])]
    public function create(Request $request, CreateUserCommandHandler $handler): Response
    {
        $command = new CreateUserCommand('test@example.com', '12345');
        $form = $this->createForm(UserType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Created organisation');

                return $this->redirectToRoute('index');
            } catch (Exception $e) {
                throw $e;
            }
        }

        return $this->render('pages/user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
