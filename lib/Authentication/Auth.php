<?php

namespace Lib\Authentication;

use App\Models\User;

class Auth
{
    public static function login($user): void
    {
        $_SESSION['user']['id'] = $user->id; //aqui concentra as informações da sessão através de um log in
    }

    //retorna o usuário logado na sessão ou null
    public static function user(): ?User
    {
        if (isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            return User::findById($id);
        }

        return null;
    }

    //verifica se tem um usuário logado
    public static function check(): bool
    {
        return isset($_SESSION['user']['id']) && self::user() !== null;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']['id']);
    }
}
