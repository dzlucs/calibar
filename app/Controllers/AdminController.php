<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;
use Lib\FlashMessage;
use Core\Http\Request;
use App\Middleware\Authenticate;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Protege a rota: só admin acessa
        (new Authenticate('admin'))->handle($request);

        // Renderiza a view do painel admin
        $this->render('admin/dashboard');
    }
}