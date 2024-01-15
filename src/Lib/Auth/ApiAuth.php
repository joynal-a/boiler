<?php

namespace Abedin\Boiler\Lib\Auth;

use Abedin\Boiler\Lib\Code\ControllerCode;
use Abedin\Boiler\Lib\Code\RouteCode;
use Abedin\Boiler\Lib\Generator\Controller;
use Abedin\Boiler\Lib\Generator\Request;
use Abedin\Boiler\Lib\Generator\Resource;
use Abedin\Boiler\Lib\Manager\ReadFile;
use Abedin\Boiler\Lib\Manager\WriteFile;
use Composer\InstalledVersions;

class ApiAuth
{
    public static function run(string $type, bool $isCreteUserRepo)
    {
        $directory = app_path('Http/Controllers/API');
        $filePath = $directory . '/AuthController.php';

        $useClasses = [
            'use App\Http\Requests\SigninRequest;',
            'use App\Http\Resources\UserResource;',
            'use Illuminate\Support\Facades\Hash;',
            'use Illuminate\Http\Response;',
        ];

        $useClasses[] = match($isCreteUserRepo){
            true => 'use App\Repositories\UserRepository;',
            default => 'use App\Models\User;'
        };

        if(file_exists($filePath)){
            $fileLines = file($filePath);
            $diffFileLines = array_diff($fileLines, ["\n"]);

            $existsMethods = ReadFile::checkMethodIsExists($diffFileLines, ['signin(', 'signout(', 'authenticate(', 'getAccessToken(']);

            if(!empty($existsMethods)){
                $existsMethods = implode(', ', $existsMethods);
                $existsMethods = str_replace('(', '', $existsMethods);
                return $existsMethods;
            }

        }else{
            Controller::generate('AuthController', $directory, 'namespace App\Http\Controllers\API;');
        }

        Request::generate('SigninRequest', "'email' => 'required|email|exists:users,email',\n                        'password' => 'required'");
        Resource::generate('UserResource', "[
            'name' => \$this->name,
            'email' => \$this->email,
        ]");

        $controllerPath = app_path('Http/Controllers/Controller.php');
        ReadFile::ignoreOrWrite($controllerPath, ['json('], ControllerCode::writeJsonMethod());

        if($type == 'passport'){
            $checkIsPackage = InstalledVersions::isInstalled('laravel/passport');
            if(!$checkIsPackage){
                shell_exec('composer require laravel/passport');
                shell_exec('php artisan passport:install');
            }

            self::authWithPassport();
            // authenticate code write on this method
            WriteFile::addNewCode($filePath, ControllerCode::apiAuth($isCreteUserRepo, 'passport'), $useClasses, 'namespace');
        }

        if($type == 'sanctum'){
            $checkIsPackage = InstalledVersions::isInstalled('laravel/sanctum');
            if(!$checkIsPackage){
                shell_exec('composer require laravel/sanctum');
            }

            self::authWithSanctum();

            WriteFile::addNewCode($filePath, ControllerCode::apiAuth($isCreteUserRepo, 'sanctum'), $useClasses, 'namespace');
        }

        $routeFile = base_path('routes/api.php');

        $classes = [
            'use App\Http\Controllers\API\AuthController;',
        ];

        WriteFile::addNewRoutes($routeFile, RouteCode::apiAuth(), $classes, '<?php');
    }

    private static function authWithPassport()
    {
        // set up config file
        $authConfigPath = base_path('config/auth.php');
        $authConfigFileLines = file($authConfigPath);
        // Remove all empty lines
        $diffAuthConfigFileLines = array_diff($authConfigFileLines, ["\n"]);
        $needConfigure = ReadFile::searchWordToGetLineNo($diffAuthConfigFileLines, "'driver' => 'passport'");
        if(!$needConfigure){
            // ignire all empty lines
            $existTargetLine = ReadFile::searchWordToGetLineNo($diffAuthConfigFileLines, "'api' => [");
            if($existTargetLine){
                $targetLine = ReadFile::searchWordToGetLineNo($diffAuthConfigFileLines, "'driver' => 'sanctum',");
                $authConfigFileLines[$targetLine] = "            'driver' => 'passport',\n";
            }else{
                $targetLine = ReadFile::searchWordToGetLineNo($diffAuthConfigFileLines, "'guards' => [") + 4;
                $authConfigFileLines[$targetLine] = "        ],\n\n        'api' => [\n            'driver' => 'passport',\n            'provider' => 'users',\n        ],\n";
            }
        }
        file_put_contents($authConfigPath, implode('', $authConfigFileLines));

        // Write User model
        $userModelPath = app_path('Models/User.php');
        $userModelPathFileLines = file($userModelPath);
        $diffUserModelFileLines = array_diff($userModelPathFileLines, ["\n"]);
        // Remove all empty lines
        $existsPassportClass = ReadFile::checkMethodIsExists($diffUserModelFileLines, ['Laravel\Passport\HasApiTokens']);

        if(empty($existsPassportClass)){
            $targetLine = ReadFile::searchWordToGetLineNo($diffUserModelFileLines, 'Laravel\Sanctum\HasApiTokens');
            $userModelPathFileLines[$targetLine] = "use Laravel\Passport\HasApiTokens;\n";
            file_put_contents($userModelPath, implode('', $userModelPathFileLines));
        }
    }

    private static function authWithSanctum()
    {
         // set up config file
        $authConfigPath = base_path('config/auth.php');
        $authConfigFileLines = file($authConfigPath);

        $diffAuthConfigFileLines = array_diff($authConfigFileLines, ["\n"]);
        $needConfigure = ReadFile::searchWordToGetLineNo($diffAuthConfigFileLines, "'driver' => 'sanctum',");
        if(!$needConfigure){
            $existTargetLine = ReadFile::searchWordToGetLineNo($diffAuthConfigFileLines, "'api' => [");
            if($existTargetLine){
                $targetLine = ReadFile::searchWordToGetLineNo($diffAuthConfigFileLines, "'driver' => 'passport',");
                $authConfigFileLines[$targetLine] = "            'driver' => 'sanctum',\n";
            }else{
                $targetLine = ReadFile::searchWordToGetLineNo($diffAuthConfigFileLines, "'guards' => [") + 4;
                $authConfigFileLines[$targetLine] = "        ],\n\n        'api' => [\n            'driver' => 'sanctum',\n            'provider' => 'users',\n        ],\n";
            }
        }
        file_put_contents($authConfigPath, implode('', $authConfigFileLines));

        // Write User model
        $userModelPath = app_path('Models/User.php');
        $userModelPathFileLines = file($userModelPath);
        $diffUserModelFileLines = array_diff($userModelPathFileLines, ["\n"]);
        // Remove all empty lines
        $existsPassportClass = ReadFile::checkMethodIsExists($diffUserModelFileLines, ['Laravel\Sanctum\HasApiTokens']);

        if(empty($existsPassportClass)){
            $targetLine = ReadFile::searchWordToGetLineNo($diffUserModelFileLines, 'Laravel\Passport\HasApiTokens');
            $userModelPathFileLines[$targetLine] = "use Laravel\Sanctum\HasApiTokens;\n";
            file_put_contents($userModelPath, implode('', $userModelPathFileLines));
        }
    }
}