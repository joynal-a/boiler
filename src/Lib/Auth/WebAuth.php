<?php

namespace Abedin\Boiler\Lib\Auth;

use Abedin\Boiler\Lib\Code\ControllerCode;
use Abedin\Boiler\Lib\Code\RouteCode;
use Abedin\Boiler\Lib\Generator\Controller;
use Abedin\Boiler\Lib\Generator\Request;
use Abedin\Boiler\Lib\Manager\ReadFile;
use Abedin\Boiler\Lib\Manager\WriteFile;

class WebAuth
{
    /**
     * Make web controller and routes
     * @return void|string
     */
    public static function run(bool $isCreteUserRepo)
    {
        $directory = app_path('Http/Controllers/Web');
        $filePath = $directory . '/AuthController.php';

        $useClasses = [
            'use Illuminate\Support\Facades\Auth;',
            'use Illuminate\Http\Request;',
            'use Illuminate\Support\Facades\Hash;',
            'use Illuminate\Support\Facades\Session;'
        ];

        $useClasses[] = match($isCreteUserRepo){
            true => 'use App\Repositories\UserRepository;',
            default => 'use App\Models\User;'
        };

        if(file_exists($filePath)){
            $fileLines = file($filePath);
            $diffFileLines = array_diff($fileLines, ["\n"]);

            $existsMethods = ReadFile::checkMethodIsExists($diffFileLines, ['signin(', 'signout(', 'index(', 'isAuthenticate(']);

            if(!empty($existsMethods)){
                $existsMethods = implode(', ', $existsMethods);
                $existsMethods = str_replace('(', '', $existsMethods);
                return $existsMethods;
            }
        }else{
            Controller::generate('AuthController', $directory, 'namespace App\Http\Controllers\Web;');
        }
        Request::generate('SigninRequest', "'email' => 'required|email|exists:users,email',\n                        'password' => 'required'");
        // authenticate code write on this method
        WriteFile::addNewCode($filePath, ControllerCode::webAuth($isCreteUserRepo), $useClasses, 'namespace');

        $routeFile = base_path('routes/web.php');

        $classes = [
            'use App\Http\Controllers\Web\AuthController;',
        ];

        WriteFile::addNewRoutes($routeFile, RouteCode::webAuth(), $classes, '<?php');
    }
}