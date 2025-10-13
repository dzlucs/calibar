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
    //código alterado para que não seja possível acessar a tela de login já estando autenticado
    public function new(Request $request): void
    {
        if (!Auth::check()) {
            $title = 'Login';
            $this->render('authentication/login', compact('title'), 'login');
        } else {
            FlashMessage::danger('Você já está logado(a)!');
            $user = Auth::user();
            $this->redirectLoggedUser($user);
        }
    }

    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        $user = User::findBy(['email' => $params['email']]);

        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);
            FlashMessage::success('Login realizado com sucesso!');
            $this->redirectLoggedUser($user);
        } else {
            FlashMessage::danger('Email e/ou senha inválidos!');
            $this->redirectTo(route('users.login'));
        }
    }

    public function checkLogin(Request $request): void
    {
        $user = Auth::user();

        if ($user) {
            $this->redirectLoggedUser($user);
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

    public function redirectLoggedUser(User $user): void
    {
        if ($user->isAdmin()) {
            $this->redirectTo(route('admin.index'));
        } else {
            $this->redirectTo(route('customer.index'));
        }
    }
}
