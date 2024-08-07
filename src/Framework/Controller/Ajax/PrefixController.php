<?php

namespace App\Framework\Controller\Ajax;

use App\Domain\Company\CompanyRepositoryException;
use App\Domain\Company\CompanyRepositoryInterface;
use App\Framework\Controller\AbstractController;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/ajax/prefix', name: 'ajax.prefix.', methods: ['GET'])]
class PrefixController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route(path: '/', name: 'get', methods: ['GET'])]
    public function __invoke(Request $request, CompanyRepositoryInterface $repository): JsonResponse
    {
        $companyName = $request->get('companyName', '');
        if ('' === $companyName) {
            return new JsonResponse(['prefix' => '']);
        }

        try {
            return new JsonResponse(['prefix' => $repository->generatePrefix($companyName)]);
        } catch (CompanyRepositoryException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
