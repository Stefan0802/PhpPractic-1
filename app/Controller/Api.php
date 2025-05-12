<?php

namespace Controller;

use Model\Post;
use Model\Telephone;
use Model\User;
use Src\View;
use Src\Request;
use Src\Auth\Auth;
use Src\Validator\Validator;
class Api
{
    public function index(): void
    {
        $posts = Post::all()->toArray();

        (new View())->toJSON($posts);
    }

    public function echo(Request $request): void
    {
        (new View())->toJSON($request->all());
    }

    public function api_register(Request $request): void
    {
        $data = $request->all();

        $validator = new Validator($data, [
            'login' => ['required', 'unique:users,login'],
            'password' => ['required', 'min:6']
        ]);

        if ($validator->fails()) {
             (new View())->toJSON(['errors' => $validator->errors()]);
        }

        $user = User::create([
            'login' => $data['login'],
            'password' => $data['password'],
            'name' => $data['name'] ?? ''
        ]);

        if ($user) {
            $token = Auth::generateToken($user->id);
            (new View())->toJSON(['message' => 'Пользователь зарегистрирован', 'token' => $token]);
        }


        (new View())->toJSON(['error' => 'Не удалось создать пользователя']);
    }

    public function api_login(Request $request): void
    {
        $credentials = $request->all();


        if (!$user = Auth::attempt($credentials)) {
             (new View())->toJSON(['error' => 'Неправильный логин или пароль']);
        }

        $user = User::searchUser($credentials['login']);

        $token = Auth::generateToken($user->id);

         (new View())->toJSON([
            'message' => 'Авторизация успешна',
            'token' => $token,
            'user' => $user,
//            'user' => $user->only(['id', 'login', 'name'])
        ]);

    }


    public function home(Request $request): void
    {
        $user = $request->get('user');

         (new View())->toJSON([
            'user' => $user->only(['id', 'login', 'name'])
        ]);
    }


    public function create_number(Request $request): void
    {
        $data = $request->all();
        $validator = new Validator($data, [
            'number' => ['required'],
        ], [
            'required' => 'Поле :field пусто',
        ]);

        if($validator->fails()){
            (new View())->toJSON(['errors' => $validator->errors()]);

        }

        if(Telephone::create($data)){
            (new View())->toJSON([
                'access' => 'номер успешн создан',
                'number' => $data['number']
                ]);
        }

         (new View())->toJSON(['errors' => 'не получилось создать номер']);
    }

    public function phone(Request $request): void
    {
        $phones = Telephone::all();


         (new View())->toJSON(['phones' => $phones]);
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


