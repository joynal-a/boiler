<?php 

namespace Abedin\Boiler\Lib\Manager; 

class WriteFile 
{
    
    public static function addNewCode(string $filePath,string $code, array $useClasses = [], $classImport = null): void
    {
        // Open File Line By line
        $fileLines = file($filePath);
        // Remove all empty lines
        $diffFileLines = array_diff($fileLines, ["\n"]);
        // target a line for write code
        $targetLine = (key(array_slice($diffFileLines, -1, 1, true)));
        // import code in the line

        $code .= "\n\n}";
        $fileLines[$targetLine] = $code;

        if(!empty($useClasses)){
            $lineNo = ReadFile::searchWordToGetLineNo($diffFileLines, $classImport);
            $classes = [];
            foreach($useClasses as $useClass){
                if(is_null(ReadFile::searchWordToGetLineNo($diffFileLines, $useClass))){
                    $classes[] = $useClass . "\n";
                };
            }
            $classes = implode("", $classes);
            if($classes){
                $fileLines[$lineNo] = $fileLines[$lineNo] . "\n" . $classes;
            }
        }

        // save code in the file
        file_put_contents($filePath, implode('', $fileLines));
    }

    public static function addNewRoutes($filePath, string $routes, array $useClasses = [], $whereImportClass = null): void
    {
        $fileLines = file($filePath);
        // Remove all empty lines
        $diffFileLines = array_diff($fileLines, ["\n"]);
        $targetLine = (key(array_slice($diffFileLines, -1, 1, true))) + 1;

        if(!empty($useClasses)){
            $lineNo = ReadFile::searchWordToGetLineNo($diffFileLines, $whereImportClass);
            $classes = [];
            foreach($useClasses as $useClass){
                if(is_null(ReadFile::searchWordToGetLineNo($diffFileLines, $useClass))){
                    $classes[] = $useClass . "\n";
                };
            }
            $classes = implode("", $classes);
            if($classes){
                $fileLines[$lineNo] = $fileLines[$lineNo] . "\n" . $classes;
            }
        }
        $routes .= "\n\n";
        $fileLines[$targetLine] = $routes;
        file_put_contents($filePath, implode('', $fileLines));
    }
}