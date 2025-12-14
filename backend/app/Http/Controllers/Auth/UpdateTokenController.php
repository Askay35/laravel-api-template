<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Contracts\Services\AuthServiceContract;
use App\Http\Resources\UserResource;
use App\DTO\Auth\LoginDTO;
use Symfony\Component\HttpFoundation\Request;

class UpdateTokenController extends Controller
{
    public function __invoke(Request $request, AuthServiceContract $authService): UserResource
    {
        $user = $request->user();
        $authService->updateToken($user, $request->cookies->get('refresh_token'));
        
        return response()->json();
    }
}
