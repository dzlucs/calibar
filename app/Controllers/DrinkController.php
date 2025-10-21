<?php

namespace App\Controllers;

use App\Models\Drink;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class DrinkController extends Controller
{
    public function index(Request $request): void
    {
        $drinks = $this->current_user->admin()->drinks()->get();
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';
        $this->render('admin/drinks/index', compact('drinks', 'imagePath'), 'dashboard');
    }
}
