<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate{

    public static function populate(): void {
        
        $user1 = new User([
            'name' => 'user_example',
            'email' => 'user@exemplo.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        if ($user1->save()) {
            echo "User saved!\n";
        } else {
            var_dump($user1->errors);
        }
    }
}