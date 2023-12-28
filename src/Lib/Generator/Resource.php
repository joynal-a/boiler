<?php

namespace Abedin\Boiler\Lib\Generator; 

class Resource {
    public static function generate($className, $returnData): void
    {
        $directory = app_path('Http/Resources');
        if(!file_exists($directory .'/UserResource.php')){
            if (!is_dir($directory)) {
                mkdir($directory);
            }
            
$code = "<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class $className extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request \$request): array
    {
        return $returnData;
    }
}";

            $filePath = $directory .'/'. 'UserResource.php';
            file_put_contents($filePath, $code);
        }
    }
}