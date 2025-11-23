<?php

namespace App\Controllers;

use App\Models\Drink;
use App\Models\DrinkImage;
use App\Services\DrinkGallery;
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
        $imagePath = '/assets/images/defaults/boy-profile.jpeg';

        $params = $request->getParams();

        /** @var Drink $drink */
        $drink = $this->current_user->admin()->drinks()->new($params['drink']);

        $image = $_FILES['drink_image'];

        if ($drink->save()) {
            FlashMessage::success('Drink registrado com sucesso!');

            $drinkImage = new DrinkImage([
                'drink_id' => $drink->id,
                'image_name' => null
            ]);

            if ($drinkImage->gallery()->create($image)) {
                FlashMessage::success('Imagem registrada com sucesso!');
            } else {
                FlashMessage::danger('Problemas ao registrar a imagem!');
            }

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

        /** @var Drink $drink */
        $drink = $this->current_user->admin()->drinks()->findById($params['drink_id']);
        $id = $params['drink_id'];

        if (DrinkGallery::destroyAllDrinkImages($id)) {
            $drink->destroy();
            FlashMessage::success('Drink removido com sucesso!');
            $this->redirectTo(route('drinks.index'));
        } else {
            FlashMessage::danger('Problemas ao remover as imagens!');
            $this->redirectTo('/admin/drinks/' . $drink->id);
        }
    }

    public function destroyDrinkImage(Request $request): void
    {

        $params = $request->getParams();
        $image_name = $params['image_name'];
        $id = $params['id'];

        $image = DrinkImage::findById($id);

        if ($image->gallery()->destroyDrinkImage()) {
            FlashMessage::success('Imagem removida com sucesso!');
        } else {
            FlashMessage::danger('Problemas ao remover a imagem!');
        }

        $this->redirectTo(route('drinks.show', ['drink_id' => $image->drink_id]));
    }


    public function createDrinkImage(Request $request): void
    {
        //$imagePath = '/assets/images/defaults/boy-profile.jpeg';

        $drinkId = $_POST['drink_id'];
        $image = $_FILES['drink_image'];

        /** @var Drink $drink */
        $drink = Drink::findById($drinkId);

        /** @var DrinkImage $drinkImage */
        $drinkImage = new DrinkImage([
            'drink_id' => $drink->id,
            'image_name' => null
        ]);

        if ($drinkImage->gallery()->create($image)) {
            FlashMessage::success('Imagem registrada com sucesso!');
        } else {
            FlashMessage::danger('Problemas ao registrar a imagem!');
        }

        $this->redirectTo(route('drinks.show', ['drink_id' => $drink->id]));
    }
}
