<?php

namespace Calibar\Controllers;

use Calibar\Core\Controller;
use Calibar\Core\Request;
use Calibar\Core\Validator;
use Calibar\Core\Redirect;
use Calibar\Lib\Auth;
use Calibar\Lib\Session;
use Calibar\Models\User;

class AuthController extends Controller
{
    public function create()
    {
        // return View::render('auth.login');
    }

    public function store(Request $request)
    {
        $validator = new Validator($request->all());
        $validator->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->getErrors());
        }

        $user = User::findByEmail($request->input('email'));

        if (!$user || !$user->authenticate($request->input('password'))) {
            Session::set('error', 'Credenciais inválidas.');
            return Redirect::back();
        }
        
        $user->last_login_at = date('Y-m-d H:i:s');
        $user->save();

        Auth::login($user->id);
        Session::set('success', 'Login realizado com sucesso!');
        return Redirect::to(route('dashboard.index'));
    }

    public function destroy()
    {
        Auth::logout();
        Session::set('success', 'Você foi desconectado com segurança.');
        return Redirect::to(route('login.create'));
    }
}