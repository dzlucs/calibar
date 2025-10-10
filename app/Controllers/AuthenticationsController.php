<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthenticationsController extends Controller
{

    //renderiza a tela de login
    public function new(Request $request): void
    {
        $title = 'Login';
        $this->render('authentication/login', compact('title'), 'login');
    }
 
    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        $user = User::findBy(['email' => $params['email']]);

        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);
            FlashMessage::success('Login realizado com sucesso!');

            // Verificar tipo de usuário
            if ($user->isAdmin()) {
                $this->redirectTo(route('admin.index'));
            } else {
                $this->redirectTo(route('customer.index'));
            }
        } else {
            FlashMessage::danger('Email e/ou senha inválidos!');
            $this->redirectTo(route('users.login'));
        }
    }

    public function checkLogin(Request $request): void
    {
        $user = Auth::user();

        if ($user) {
            if ($user->isAdmin()) {
                $this->redirectTo(route('admin.index'));
            } else {
                $this->redirectTo(route('driver.index'));
            }
        } else {
            $this->redirectTo(route('users.login'));
        }
    }

    public function destroy(): void
    {
        Auth::logout();
        FlashMessage::success('Logout realizado com sucesso!');
        $this->redirectTo(route('users.login'));
    }
}
