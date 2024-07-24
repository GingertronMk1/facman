<?php

namespace App\Framework\Controller;

use App\Application\Common\CommandHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    protected function handleForm(
        CommandHandlerInterface $handler,
        mixed $command,
        string $formClass,
        Request $request,
        string $redirectUrl,
        string $template,
        array $twigContext = []
    ): Response {
        $form = $this->createForm($formClass, $command);
        $form->handleRequest($request);

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
