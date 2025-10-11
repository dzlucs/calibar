<?php

namespace App\Controllers;

use App\Models\Admin;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request): void
    {
        $title = 'Página Inicial do Administrador';
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';
        $this->render('admin/index', compact('title', 'imagePath'), 'dashboard');
    }
}
