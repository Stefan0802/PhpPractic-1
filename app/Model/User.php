<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\Auth;
use Src\Auth\IdentityInterface;
use Src\Validator\Validator;
use Src\View;

class User extends Model implements IdentityInterface
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'name',
        'lastName',
        'login',
        'password'
    ];


//    protected static function booted()
//    {
//        static::created(function ($user) {
//            $user->password = md5($user->password);
//            $user->save();
//        });
//    }

    public function tokens()
    {
        return $this->hasMany(Token::class, 'user_id');
    }

    //Выборка пользователя по первичному ключу
    public function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }

    //Возврат первичного ключа
    public function getId(): int
    {
        return $this->id;
    }

    public static function searchUser($name)
    {
        $user = self::where('login', $name)->first();
        return $user ?? '';
    }

    //Возврат аутентифицированного пользователя
    public function attemptIdentity(array $credentials)
    {
        return self::where(['login' => $credentials['login'],
            'password' => md5($credentials['password'])])->first();
    }


    public static function createUser($request)
    {
        $data = $request->all();

        $validator = new Validator($data, [
            'login' => ['required', 'unique:users,login'],
            'password' => ['required', 'min:6']
        ]);


        if($validator->errors()){
            $access = 'error';
            $message = 'не получилось создать пользоваетля';
        }else{
            $access = 'успешно';
            $message = 'Пользователь создан';
            $user = \Model\User::create([
                'login' => $data['login'],
                'password' => $data['password'],
                'name' => $data['name'] ?? ''
            ]);

            $token = Auth::generateToken($user->id);
        }

        return [
            'access' => $access,
            'message' => $message,
            'token' => $token,
            'user' => $user,
            'error' => $validator->errors()
        ];
    }

    public static function loginUser($request)
    {
        $credentials = $request->all();

        if($user = \Model\User::searchUser($credentials['login'])){
            if($user['password'] == $credentials['password']){

                $token = Auth::generateToken($user->id);

                (new View())->toJSON([
                    'message' => 'Авторизация успешна',
                    'token' => $token,
                    'user' => $user->only(['id', 'login', 'name', 'password'])
                ]);

                $access = 'access';
                $message = 'Пользоветль успешно вошел';
            }else{
                $access = 'error';
                $message = 'Не получилось войти';
                $error = 'не верный пароль пользователя';
            }
        }else{
            $access = 'error';
            $message = 'Не получилось войти';
            $error = 'не верный логин пользователя';
        }


        return [
            'access' => $access,
            'message' => $message,
            'error' =>$error,
            'user' => $user,
            'token' =>$token
        ];
    }

}