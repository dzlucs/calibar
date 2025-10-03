<?php

namespace App\Controllers;

use App\Models\Drink;
use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;
use Lib\FlashMessage;
use Core\Http\Request;

class DrinkController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Checa autenticação e papel de admin
        if (!Auth::check() || Auth::user()->role !=='admin') {
            FlashMessage::danger('Acesso negado. Apenas administradores podem fazer CRUD de drinks.');
            $this->redirectTo('/login');
        }
    }

    // Lista todas as bebidas
    public function index()
    {
        $drinks = Drink::all();
        $this->render('drinks/index', ['drinks' => $drinks]);
    }

    // Exibição de formulário de criação
    public function create()
    {
        $this->render('drinks/create');
    }
    
    // Salvando nova bebida
    public function store(Request $request)
    {
        $drink = new Drink($request->all());
        $drink->validates();

        if ($drink->hasErrors()) {
            FlashMessage::danger('Erro ao cadastrar a bebida.');
            $this->render('drinks/create', ['drink' => $drink]);
            return;
        }

        $drink->save();
        FlashMessage::success('Bebida cadastrada com sucesso!');
        $this->redirectTo('/drinks');
    }

    // Exibição do fomulário de edição
    public function edit(int $id)
    {
        $drink = Drink::findById($id);
        if (!$drink) {
            FlashMessage::danger('Bebida não encontrada.');
            $this->redirectTo('/drinks');
            return;
        }
        $this->render('drinks/edit', ['drink' => $drink]);
    }

    // Atualização de bebida
    public function update(Request $request, int $id)
    {
        $drink = Drink::findById($id);
        if (!$drink) {
            FlashMessage::danger('Bebida não encontrada');
            $this->redirectTo('/drinks');
            return;
        }

        $drink->fill($request->all());
        $drink->validates();

        if ($drink->hasErrors()) {
            FLashMessage::danger('Erro ao atualizar bebida');
            $this->render('drinks/edit', ['drink' => $drink]);
            return;
        }

        $drink->save();
        FlashMessage::success('Bebida atualizada com sucesso!');
        $this->redirectTo('/drinks');
    }

    // Deletar bebida
    public function delete(int $id)
    {
        $drink = Drink::findById($id);
        if (!$drink) {
            FlashMessage::danger('Bebida não encontrada');
            $this->redirectTo('/drinks');
            return;
        }

        $drink->delete();
        FlashMessage::success('Bebida deletada com sucesso!');
        $this->redirectTo('/drinks');
    }
}