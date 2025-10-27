<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\Drink;
use App\Models\User;
use Tests\TestCase;

/**
 * Este teste segue a estrutura do RouteTest.php (do Frota Fácil)
 * e do AdminTest.php.
 * Testa o Modelo Drink (o CRUD, validações e relacionamentos).
 */
class DrinkTest extends TestCase
{
    private User $user;
    private Admin $admin;
    private Drink $drink; // Drink base

    public function setUp(): void
    {
        parent::setUp(); // Limpa a base de dados

        // 1. Criar um Utilizador
        $this->user = new User([
            'name' => 'Admin User Test',
            'email' => 'admin@test.com',
            'encrypted_password' => password_hash('password', PASSWORD_DEFAULT)
        ]);
        $this->user->save();

        // 2. Criar um Admin
        $this->admin = new Admin([
            'user_id' => $this->user->id
        ]);
        $this->admin->save();

        // 3. Criar um Drink base
        $this->drink = new Drink([
            'name' => 'Coca-Cola',
            'price' => 5.50,
            'admin_id' => $this->admin->id
        ]);
        $this->drink->save();
    }

    /**
     * ================= START OF TESTING ====================================
     */

    public function test_should_create_a_new_drink(): void
    {
        // Cria um segundo drink
        $drink2 = new Drink([
            'name' => 'Pepsi',
            'price' => 4.50,
            'admin_id' => $this->admin->id
        ]);
        
        $this->assertTrue($drink2->save());
        $this->assertCount(2, Drink::all()); // O drink do setUp + este
    }

    public function test_should_return_all_drinks(): void
    {
        $drinks[] = $this->drink; // O drink do setUp

        // Cria um segundo drink
        $drink2 = new Drink([
            'name' => 'Pepsi',
            'price' => 4.50,
            'admin_id' => $this->admin->id
        ]);
        $drinks[] = $drink2;
        $drink2->save();

        $all = Drink::all();
        $this->assertCount(2, $all);
        $this->assertEquals($drinks, $all); // Compara os arrays de objetos
    }

    public function test_destroy_should_remove_a_drink(): void
    {
        // Cria um segundo drink
        $drink2 = new Drink([
            'name' => 'Pepsi',
            'price' => 4.50,
            'admin_id' => $this->admin->id
        ]);
        $drink2->save();

        $this->assertCount(2, Drink::all());
        $this->assertTrue($drink2->destroy()); // Apaga o segundo drink
        $this->assertCount(1, Drink::all()); // Deve sobrar apenas o drink do setUp
    }

    public function test_should_edit_drink_attributes(): void
    {
        // Edita o drink do setUp
        // CORREÇÃO: Trocado $this. por $this->
        $this->drink->name = 'Guaraná Antarctica';
        $this->drink->price = 6.00;

        $this->assertTrue($this->drink->save());
        
        // Busca novamente para garantir que foi salvo
        $edited_drink = Drink::findById($this->drink->id);

        $this->assertEquals('Guaraná Antarctica', $edited_drink->name);
        $this->assertEquals(6.00, $edited_drink->price);
    }


    public function test_should_return_error_if_attributes_are_empty(): void
    {
        // Testa a validação (baseado no seu Drink.php)
        $drink_com_erro = new Drink([
            'name' => '', // Nome vazio
            'price' => null,
            'admin_id' => $this->admin->id
        ]);

        $this->assertFalse($drink_com_erro->isValid());
        $this->assertTrue($drink_com_erro->hasErrors());
        
        // CORREÇÃO: O teste agora espera a mensagem em Português
        $this->assertStringContainsString('não pode ser vazio', $drink_com_erro->errors('name'));
    }

    public function test_should_return_error_if_admin_id_does_not_exist(): void
    {
        // Testa a validação (baseado no seu Drink.php)
        $drink_com_erro = new Drink([
            'name' => 'Fanta Uva',
            'price' => 5.00,
            'admin_id' => 99999 // Um ID que não existe
        ]);

        $this->assertFalse($drink_com_erro->isValid());
        $this->assertTrue($drink_com_erro->hasErrors());
        
        // Verifica a mensagem de erro específica do seu Drink.php
        $this->assertStringContainsString('does not exist', $drink_com_erro->errors('admin_id'));
    }

    public function test_should_return_associated_admin(): void
    {
        // Testa a relação 'belongsTo'
        $admin = $this->drink->admin()->get();

        $this->assertInstanceOf(Admin::class, $admin);
        $this->assertEquals($this->admin->id, $admin->id);
    }
}

