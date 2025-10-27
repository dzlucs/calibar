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
        $paginator = Drink::paginate(page: $request->getParam('page', 1));
        $drinks = $paginator->registers();

        //$drinks = $this->current_user->admin()->drinks()->get();
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';
        $this->render('admin/drinks/index', compact('drinks', 'imagePath', 'paginator'), 'dashboard');
    }

    //MÉTODO PARA MOSTRAR UM DRINK EM ESPECÍFICO
    public function show(Request $request): void
    {
        $params = $request->getParams();
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';

        /** @var Drink $drink */
        $drink = $this->current_user->admin()->drinks()->findById($params['drink_id']);

        $this->render('admin/drinks/show', compact('drink', 'imagePath'));
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
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';

        if ($drink->save()) {
            FlashMessage::success('Drink registrado com sucesso!');
            $this->redirectTo(route('drinks.index'));
        } else {
            FlashMessage::danger('Existem dados incorretos! Por favor verifique');
            $this->render('admin/drinks/new', compact('drink', 'imagePath'));
        }
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';
        $drink = $this->current_user->admin()->drinks()->findById($params['drink_id']);

        $this->render('admin/drinks/edit', compact('drink', 'imagePath'));
    }

    public function update(Request $request): void
    {
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';

        $params = $request->getParams();
        $drinkParams = $params['drink'];

        $id = $params['drink_id'];

/*         var_dump($params);
        die(); */

        /** @var \App\Models\Drink $drink */
        $drink = $this->current_user->admin()->drinks()->findById($id);
        $drink->name = $drinkParams['name'];
        $drink->price = $drinkParams['price'];

        if ($drink->save()) {
            FlashMessage::success('Drink editado com sucesso!');
            $this->redirectTo('/admin/drinks/' . $drink->id);
        } else {
            FlashMessage::danger('Existem dados incorretos. Por favor, verifique.');
            $this->render('admin/drinks/edit', compact('drink', 'imagePath'));
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();
        $drink = $this->current_user->admin()->drinks()->findById($params['drink_id']);
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';

        $drink->destroy();

        FlashMessage::success('Drink removido com sucesso!');
        $this->redirectTo(route('drinks.index'));
    }
}
