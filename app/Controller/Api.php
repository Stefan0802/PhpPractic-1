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



    public function home(Request $request): void
    {
        $user = $request->get('user');

         (new View())->toJSON([
            'user' => $user->only(['id', 'login', 'name'])
        ]);
    }





}


