<?php

namespace Controller;

use Illuminate\Database\Eloquent\Model;
use Model\Post;
use Model\Telephone;
use Model\User;
use Src\View;
use Src\Request;
use Src\Auth\Auth;
use Src\Validator\Validator;
class Phone
{

    public function create_number(Request $request): void
    {

        $data = Telephone::createNumber($request);

        if($data['error'] != '' ){
            (new View())->toJSON(['errors' => $data['error']]);
        }

        (new View())->toJSON([
            'access' => $data['access'],
            'number' => $data['number']
        ]);

    }

    public function phone(Request $request): void
    {
        $phones = Telephone::all();
        (new View())->toJSON(['phones' => $phones]);
    }

}









