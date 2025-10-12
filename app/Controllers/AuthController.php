<?php

namespace Calibar\Controllers;

use Calibar\Core\Controller;
use Calibar\Core\Request;
use Calibar\Core\Validator;
use Calibar\Core\Redirect;
use Calibar\Lib\Auth;
use Calibar\Lib\Session;
use Calibar\Models\User;

class AuthenticationsController extends Controller
{
    public function new(Request $request): void
    {
        $title = 'Login';
        $this->render('authentications/new', compact('title'), 'authLayout');
    }

    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        $user = User::findBy(['cpf' => $params['cpf']]);

        $user->last_login_at = date('Y-m-d H:i:s');
        $user->save();

        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);
            FlashMessage::success('Login realizado com sucesso!');

            // Verificar tipo de usuário
            if ($user->isDriver()) {
                $this->redirectTo(route('driver.index'));
            } else {
                $this->redirectTo(route('fleets.index'));
            }
        } else {
            FlashMessage::danger('CPF e/ou senha inválidos!');
            $this->redirectTo(route('users.login'));
        }
    }

    public function checkLogin(Request $request): void
    {
        $user = Auth::user();

        if ($user) {
            if ($user->isManager()) {
                $this->redirectTo(route('fleets.index'));
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