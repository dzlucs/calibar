<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class CustomerAuthenticate implements Middleware
{
    public function handle(Request $request): void
    {
        $user = Auth::user();

        if ($user === null) {
            FlashMessage::danger('Você não tem permissão para acessar essa página!');
            $this->redirectTo(route('users.login'));
        }

        if ($user->isAdmin()) {
            FlashMessage::danger('Apenas Clientes tem permissão para acessar essa página!');
            $this->redirectTo(route('admin.index'));
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
