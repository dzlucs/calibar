<?php

namespace App\Controllers;

use App\Models\Drink;
use Lib\FlashMessage;
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

    //MÉTODO PARA MOSTRAR UM DRINK EM ESPECÍFICO
    public function show(Request $request): void
    {
        $params = $request->getParams();

        //pegue o usuário atual, busque o id de admin dele, busque todos os drinks associados a esse admin e busque pelo drink específico via id
        /** @var Drink $drink */
        $drink = $this->current_user->admin()->drinks()->findById($params['drink_id']);

        $this->render('admin/drinks/show', compact('drink'));
    }

    public function new(): void
    {
        //criando uma nova instância de um drink
        $drink = $this->current_user->admin()->drinks()->new();
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';
        $this->render('admin/drinks/new', compact('drink',  'imagePath'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $drink = $this->current_user->admin()->drinks()->new($params['drink']);

        if ($drink->save()) {
            FlashMessage::success('Drink registrado com sucesso');
            $this->redirectTo(route('drinks.index'));
        } else {
            FlashMessage::danger('Dados incorretos. Verifique.');
            $imagePath = '/assets/images/defaults/boy-profile.jpeg';
            $this->render('admin/drinks/new', compact('drink', 'imagePath'));
        }
    }

    public function edit(Request $request):void
    {
        $params = $request->getParams();
        $drink = $this->current_user->admin()->drinks()->findById($params['drink_id']);

        $this->render('admin/drinks/edit', compact('drink'));
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('drink_id');
        $params = $request->getParam('drink');

        /**  @var \App\Models\Drink $drink */
        $drink = $this->current_user->admin()->drinks()->findById($id);
        $drink->name = $params['name'];

        if ($drink->save()) {
            FlashMessage::success('Drink editado com sucesso!');
            $this->redirectTo('admin/drinks/' . $drink->id);
        } else {
            FlashMessage::danger('Dados incorretos. Verifique');
            $this->render('admin/drinks/edit', compact('drink'));
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $drink = $this->current_user->admin()->drinks()->findById($params['drink_id']);
        $drink->destroy();

        FlashMessage::success('Drink removido com sucesso');
        $this->redirectTo(route('drinks.index'));
    }

}
