<?php

namespace Abedin\Boiler\Lib\Generator;

class Model 
{
    /**
     * The name and code function will be generate model.
     *
     * @var string
     */
    public static function generate($className): void
    {
$modelClassContent = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class $className extends Model
{
    use HasFactory;
}";

        $filePath = app_path("Models/{$className}.php");
        file_put_contents($filePath, $modelClassContent);
    }

    public static function existsModels(): array
    {
        // get all models from Model directory
        $directory = scandir(app_path('Models'));
        $existsModels = array_diff($directory, ['.', '..']);
        $models = [];

        foreach($existsModels as $model){
            // remove model extension
            $models[] = str_replace('.php', '', $model);
        }
        return $models;
    }
}
