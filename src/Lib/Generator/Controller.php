<?php

namespace Abedin\Boiler\Lib\Generator;

class Controller 
{

public static function generate(string $className, string $directory, string $namespache): void
{
    if (!is_dir($directory)) {
        mkdir($directory);
    }

$modelClass = "<?php

$namespache

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class $className  extends Controller
{
    // Code here 
}";

    $filePath = $directory .'/'. $className . '.php';
    file_put_contents($filePath, $modelClass);
}
}