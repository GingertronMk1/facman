<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Application\Site\CommandHandler\CreateSiteCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends AbstractController
{
    public function create(
        CreateSiteCommandHandler $handler,
        Request $request
    ): Response {}
}
