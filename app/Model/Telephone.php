<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Validator\Validator;
use Src\View;

use Model\Post;
use Model\User;
use Src\Request;
use Src\Auth\Auth;



class Telephone extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'number'

    ];





    public static function createNumber($request)
    {
        $data = $request->all();
        $validator = new Validator($data, [
            'number' => ['required'],
        ], [
            'required' => 'Поле :field пусто',
        ]);

        Telephone::create($data);

        return  [
            'access' => 'успешно',
            'number' => $data['number'],
            'error' => $validator->errors()
        ];
    }

}