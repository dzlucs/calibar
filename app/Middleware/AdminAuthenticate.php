<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AdminAuthenticate implements Middleware
{
    public function handle(Request $request): void
    {
        $user = Auth::user();

        if ($user === null) {
            FlashMessage::danger('Você não tem permissão para acessar essa página!');
            $this->redirectTo(route('users.login'));
        }

        if ($user->isCustomer()) {
            FlashMessage::danger('Apenas Administradores tem permissão para acessar essa página!');
            $this->redirectTo(route('customer.index'));
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}