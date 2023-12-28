<?php

namespace Abedin\Boiler\Lib\Generator;

class Request {
    public static function generate(string $className, $validation): void
    {
        $directory = app_path('Http/Requests');
        if(!file_exists($directory . $className .'.php')){
            if (!is_dir($directory)) {
                mkdir($directory);
            }

            $code = "<?php

            namespace App\Http\Requests;

            use Illuminate\Foundation\Http\FormRequest;

            class $className extends FormRequest
            {
                /**
                 * Determine if the user is authorized to make this request.
                 */
                public function authorize(): bool
                {
                    return true;
                }

                /**
                 * Get the validation rules that apply to the request.
                 *
                 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
                 */
                public function rules(): array
                {
                    return [
                        $validation
                    ];
                }
            }";

            $filePath = $directory .'/'. $className . '.php';
            file_put_contents($filePath, $code);
        }
    
    }
}