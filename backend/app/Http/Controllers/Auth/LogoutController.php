<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\AuthServiceContract;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function __invoke(Request $request, AuthServiceContract $authService): MessageResource
    {
        $authService->logout($request->user());

        return MessageResource::make(__('messages.logged_out_successfully'));
    }
}
