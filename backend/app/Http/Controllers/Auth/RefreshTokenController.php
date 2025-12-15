<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Contracts\Services\AuthServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RefreshTokenController extends Controller
{
    public function __invoke(Request $request, AuthServiceContract $authService): JsonResponse
    {

        $user = $request->user();
        $refreshToken = $request->cookies->get('refresh_token');
        
        $response = $authService->updateToken($user, $refreshToken);
        
        return $response;
    }
}
