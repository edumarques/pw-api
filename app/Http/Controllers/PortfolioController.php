<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\RequestException;
use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;

final class PortfolioController extends AbstractController
{
    private PortfolioService $service;


    public function __construct(PortfolioService $portfolioService)
    {
        $this->service = $portfolioService;
    }


    /**
     * @return JsonResponse
     * @throws RequestException
     */
    public function user(): JsonResponse
    {
        return $this->json($this->service->getUserData());
    }


    /**
     * @return JsonResponse
     * @throws RequestException
     */
    public function repos(): JsonResponse
    {
        return $this->json($this->service->getReposData());
    }
}
