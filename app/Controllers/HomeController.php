<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $title = '';
        $this->render('layouts/login', compact('title'));
    }
}
