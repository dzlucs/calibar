<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\Drink;
use App\Models\User;
use Tests\TestCase;

class AdminTest extends TestCase
{
    private User $user;
    private Admin $admin;

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
    }

    /**
     * ================= START OF TESTING ====================================
     */

    public function test_should_create_a_new_admin(): void
    {
        $user2 = new User([
            'name' => 'Admin User 2',
            'email' => 'admin2@test.com',
            'encrypted_password' => password_hash('password', PASSWORD_DEFAULT)
        ]);
        $user2->save();

        $admin2 = new Admin([
            'user_id' => $user2->id
        ]);

        $this->assertTrue($admin2->save());
        $this->assertCount(2, Admin::all()); // O admin do setUp + este
    }

    public function test_should_return_all_admins(): void
    {
        $admins[] = $this->admin; // O admin do setUp

        $user2 = new User([
            'name' => 'Admin User 2',
            'email' => 'admin2@test.com',
            'encrypted_password' => password_hash('password', PASSWORD_DEFAULT)
        ]);
        $user2->save();
        $admin2 = new Admin([
            'user_id' => $user2->id
        ]);
        $admins[] = $admin2;
        $admin2->save();

        $all = Admin::all();
        $this->assertCount(2, $all);
        $this->assertEquals($admins, $all);
    }

    public function test_destroy_should_remove_an_admin(): void
    {
        $user2 = new User([
            'name' => 'Admin User 2',
            'email' => 'admin2@test.com',
            'encrypted_password' => password_hash('password', PASSWORD_DEFAULT)
        ]);
        $user2->save();
        $admin2 = new Admin([
            'user_id' => $user2->id
        ]);
        $admin2->save();

        $this->assertCount(2, Admin::all());
        $this->assertTrue($admin2->destroy()); // Apaga o segundo
        $this->assertCount(1, Admin::all()); // Deve sobrar apenas o do setUp
    }

    public function test_should_return_error_if_user_id_does_not_exist(): void
    {
        $admin_com_erro = new Admin([
            'user_id' => 99999 // ID que não existe
        ]);

        $this->assertFalse($admin_com_erro->isValid());
        $this->assertTrue($admin_com_erro->hasErrors());
        $this->assertStringContainsString('does not exist', $admin_com_erro->errors('user_id'));
    }

    // --- Testes de Relacionamento ---

    public function test_should_return_associated_user(): void
    {
        // Testa a relação 'belongsTo'
        $user = $this->admin->user()->get();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($this->user->id, $user->id);
    }

    public function test_should_return_associated_drinks(): void
    {
        // Testa a relação 'hasMany'
        $drink1 = $this->admin->drinks()->new([
            'name' => 'Coca-Cola',
            'price' => 5.50
        ]);
        $drink1->save();

        $drink2 = $this->admin->drinks()->new([
            'name' => 'Pepsi',
            'price' => 4.50
        ]);
        $drink2->save();

        $drinks = $this->admin->drinks()->get();

        $this->assertCount(2, $drinks);
        $this->assertInstanceOf(Drink::class, $drinks[0]);
        $this->assertEquals($drink1->id, $drinks[0]->id);
    }
}

