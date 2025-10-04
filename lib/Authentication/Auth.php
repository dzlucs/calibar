<?php

namespace Lib\Authentication;

use App\Models\User;

class Auth
{
    public static function login($user): void
    {
        $_SESSION['user']['id'] = $user->id; //aqui concentra as informações da sessão através de um log in
    }

    public static function user(): ?User
    {
        if (isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            return User::findById($id);
            //se existe user e id na sessão, retornamos o user
        }

        return null;
    }

    //verifica se tem uma sessão ativa
    public static function check(): bool
    {
        return isset($_SESSION['user']['id']) && self::user() !== null;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']['id']);
    }
}
