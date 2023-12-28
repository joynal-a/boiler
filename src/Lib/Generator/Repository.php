<?php

namespace Abedin\Boiler\Lib\Generator;

class Repository 
{
    /**
     * The name and code function will be generate repository.
     *
     * @var string
     */
    public static function generate($className): void
    {
        $directory = app_path('Repositories');
        if (!is_dir($directory)) {
            mkdir($directory);
        }

$repositoryClass = "<?php
namespace App\Repositories;

use Abedin\Boiler\Repositories\Repository;
use App\Models\\$className;

class {$className}Repository extends Repository
{
    public static function model()
    {
        return $className::class;    
    }
}";

        $filePath = app_path("Repositories/{$className}Repository.php");
        file_put_contents($filePath, $repositoryClass);
    }

    public static function existsRepositories(): array
    {
        $repositories = [];
        // check exists Repositories directory and ignore
        if(is_dir(app_path('Repositories'))){
            $directory = scandir(app_path('Repositories'));
            $existsRepositories = array_diff($directory, ['.', '..']);

            foreach($existsRepositories as $repository){
                // remove model extension
                $repositories[] = str_replace('.php', '', $repository);
            }
        }

        return $repositories;
    }

}
