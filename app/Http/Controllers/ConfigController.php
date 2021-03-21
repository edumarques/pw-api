<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class ConfigController extends AbstractController
{
    public function status(): JsonResponse
    {
        return $this->json(['connected' => true]);
    }
}
