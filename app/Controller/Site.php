<?php

namespace Controller;

use Model\Post;
use Src\View;
use Illuminate\Database\Capsule\Manager as DB;
class Site
{
    public function index(): string
    {
        $posts = DB::table('posts')->get();

        return  (new View())->render('site.post', ['post' => $posts]);
    }

    public function hello(): string
    {
        return new View('site.hello', ['message' => 'hello working']);
    }
}