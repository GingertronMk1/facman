<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Domain\User\UserFinderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserFinderInterface $userFinder): Response
    {
        return $this->render('pages/index/index.html.twig', ['users' => $userFinder->findAll()]);
    }
}
