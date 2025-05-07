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

    public function api_register(Request $request): string
    {
        $data = $request->all();

        $validator = new Validator($data, [
            'login' => ['required', 'unique:users,login'],
            'password' => ['required', 'min:6']
        ]);

        if ($validator->fails()) {
            return (new View())->toJSON(['errors' => $validator->errors()]);
        }

        $user = User::create([
            'login' => $data['login'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'name' => $data['name'] ?? ''
        ]);

        if ($user) {
            $token = Auth::generateToken($user->id);
            return (new View())->toJSON(['message' => 'Пользователь зарегистрирован', 'token' => $token]);
        }


        return (new View())->toJSON(['error' => 'Не удалось создать пользователя']);
    }

    public function api_login(Request $request): string
    {
        $credentials = $request->all();

        return (new View())->toJSON(['error' => 'Неправильный логин или пароль']);

        if (!$user = Auth::attempt($credentials)) {
            return (new View())->toJSON(['error' => 'Неправильный логин или пароль']);
        }

        $token = Auth::generateToken($user->id);

        return (new View())->toJSON([
            'message' => 'Авторизация успешна',
            'token' => $token,
            'user' => $user->only(['id', 'login', 'name'])
        ]);

    }


    public function home(Request $request): string
    {
        $user = $request->get('user'); // Получаем из запроса текущего пользователя

        return (new View())->toJSON([
            'message' => 'Авторизация успешна',
            'token' => $token,
            'user' => $user->only(['id', 'login', 'name'])
        ]);
    }


    public function create_number(Request $request): string
    {
        $data = $request->all();
        $validator = new Validator($data, [
            'number' => ['required'],
        ], [
            'required' => 'Поле :field пусто',
        ]);

        if($validator->fails()){
            return (new View())->toJSON(['errors' => $validator->errors()]);

        }

        if(Telephone::create($data)){
            return (new View())->toJSON([
                'access' => 'номер успешн создан',
                'number' => $data['number']
                ]);
        }

        return (new View())->toJSON(['errors' => 'не получилось создать номер']);
    }

    public function phone(Request $request): string
    {
        $phones = Telephone::all();


        return (new View())->toJSON(['phones' => $phones]);
    }
}


