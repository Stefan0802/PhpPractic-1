<?php

namespace Controller;

use Model\Post;
use Model\Telephone;
use Src\View;
use Src\Request;
use Src\Auth\Auth;
use Src\Validator\Validator;


class User
{


    public function api_register(Request $request): void
    {

        $data = \Model\User::createUser($request);

        if( $data['error'] != null ){
            (new View())->toJSON([
                'access' => $data['access'],
                'message' => $data['message'],
                'error' => $data['error']
            ]);
        }

        (new View())->toJSON([
            'access' => $data['access'],
            'message' => $data['message'],
            'token' => $data['token'],
            'user' => $data['user']
        ]);



    }

    public function api_login(Request $request): void
    {
        $data['error'] = \Model\User::loginUser($request);

        if($data['error'] != null){
            (new View())->toJSON([
                'error' => $data['error']
            ]);
        }

        (new View())->toJSON([
            'access' => $data['access'],
            'message' => $data['message'],
            'token' => $data['token'],
            'user' => $data['user']
        ]);





    }

    public function logout(): void
    {
        Auth::logout();

        (new View())->toJSON([
            'status' => 'success',
            'message' => 'пользователь успешно вышел из системы'
        ]);
    }

}