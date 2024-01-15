<?php 

namespace Abedin\Boiler\Lib\Manager; 

class ReadFile 
{

    public static function ignoreOrWrite($filePath, array $method, string $code, array $modifyAbleDatas = []):void
    {
        if(file_exists($filePath)){
            $fileLines = file($filePath);
            $diffFileLines = array_diff($fileLines, ["\n"]);

            $existsJsonMethod = self::checkMethodIsExists($diffFileLines, $method);

            if(empty($existsJsonMethod)){
                WriteFile::addNewCode($filePath, $code);
            }

            if(!empty($modifyAbleDatas)){
                $fileLines = file($filePath);
                $diffFileLines = array_diff($fileLines, ["\n"]);
                foreach($modifyAbleDatas['old'] as $key => $oldData){
                    $targetLine = self::searchWordToGetLineNo($diffFileLines, $oldData);
                    if($targetLine){
                        $fileLines[$targetLine] = $modifyAbleDatas['new'][$key];
                    }
                }
                file_put_contents($filePath, implode('', $fileLines));
            }
        }
    }

    public static function searchWordToGetLineNo(array $fileLines, $targetWord): int|null
    {
        $lastLineNumber = null;
        foreach($fileLines as $key => $line){
            if (strpos($line, $targetWord) !== false) {
                $lastLineNumber = $key;
                break;
            }
        }

        return $lastLineNumber;
    }

    public static function checkMethodIsExists(array $fileLines, array $words): array
    {
        $existsWord = [];
        foreach($words as $word){
            foreach($fileLines as $line){
                if (strpos($line, $word) !== false) {
                    $existsWord[] = $word;
                }
            }
        }

        return $existsWord;
    }
}