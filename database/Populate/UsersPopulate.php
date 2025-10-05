<?php

namespace Database\Populate;

use App\Models\Admin;
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
            echo "User succesfully saved!\n";
        } else {
            var_dump($user1->errors);
        }

        $admin1 = new Admin(['user_id' => $user1->id]);

        if ($admin1->save()) {
            echo "Admin succesfully saved!\n";
        } else {
            var_dump($admin1->errors);
        }

        $user2 = new User([
            'name' => 'user_example2',
            'email' => 'user@exemplo2.com',
            'password' => '987654',
            'password_confirmation' => '987654'
        ]);

        if ($user2->save()) {
            echo "User succesfully saved!\n";
        } else {
            var_dump($user2->errors);
        }
    }
}