<?php

namespace App\Controllers;

use App\Models\Admin;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): void
    {
        $title = 'Página Inicial do Cliente';
        $imagePath = '/assets/images/defaults/girl-profile.jpeg';
        $this->render('customer/index', compact('title', 'imagePath'), 'dashboard');
    }
}
