<?php

namespace App\Framework\Controller;

use App\Application\Common\CommandHandlerInterface;
use App\Application\Common\CommandInterface;
use App\Application\Common\Exception\CommandHandlerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends SymfonyAbstractController
{
    /**
     * @template T
     *
     * @param CommandHandlerInterface<T> $handler
     * @param CommandInterface&T         $command
     * @param array<string, mixed>       $twigContext
     *
     * @throws CommandHandlerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LogicException
     */
    protected function handleForm(
        CommandHandlerInterface $handler,
        CommandInterface $command,
        string $formClass,
        string $redirectUrl,
        string $template,
        array $twigContext = []
    ): Response {
        /** @var RequestStack $stack */
        $stack = $this->container->get('request_stack');
        $form = $this->createForm($formClass, $command);
        $form->handleRequest($stack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirect($redirectUrl);
        }

        return $this->render(
            $template,
            array_merge(
                ['form' => $form],
                $twigContext
            )
        );
    }
}
