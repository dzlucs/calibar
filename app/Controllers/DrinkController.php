<?php

namespace App\Controllers;

use App\Models\Drink;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class DrinkController extends Controller
{
    public function index(Request $request): void
    {
        $drinks = $this->current_user->admin()->drinks()->get();
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';
        $this->render('admin/drinks/index', compact('drinks', 'imagePath'), 'dashboard');
    }

    //MÉTODO PARA MOSTRAR UM DRINK EM ESPECÍFICO
    public function show(Request $request): void
    {
        $params = $request->getParams();

        /** @var Drink $drink */
        $drink = $this->current_user->admin()->drinks()->findById($params['drink_id']);

        $this->render('admin/drinks/show', compact('drink'));
    }

    public function new(): void
    {
        //criando uma nova instância de um drink
        $drink = $this->current_user->admin()->drinks()->new();
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';
        $this->render('admin/drinks/new', compact('drink', 'imagePath'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $drink = $this->current_user->admin()->drinks()->new($params['drink']);

        if ($drink->save()) {
            FlashMessage::success('Drink registrado com sucesso!');
            $this->redirectTo('drinks.index');
        } else {
            FlashMessage::danger('Existem dados incorretos! Por favor verifique');
            $this->render('admin/drinks/new', compact('drink'));
        }
    }
}
