<?php

use function Amp\File\isDirectory;
use function App\stringSnakeToCamel;
use function App\stringSnakeToPascal;

use App\CustomArrayProperty;
use App\CustomClassProperty;
use App\CustomPrimitiveProperty;
use CatPaw\Core\Attributes\Arguments;
use CatPaw\Core\Attributes\Option;
use CatPaw\Core\Directory;
use function CatPaw\Core\error;
use CatPaw\Core\File;
use function CatPaw\Core\ok;
use CatPaw\Core\Unsafe;

/**
 * 
 * @param  array<string> $arguments
 * @return void
 */
function main(
    #[Arguments] array $arguments = [],
    #[Option("--starts-with")] string $startWith = '',
):Unsafe {
    if (count($arguments) < 1) {
        return error("Input argument is required, for example `php jtp.phar Payload.json`.");
    }
    
    [$input] = $arguments;


    if ($isDirectory = isDirectory($input)) {
        $flat = Directory::flat($input)->try($error);
        if ($error) {
            return error($error);
        }
    } else {
        $flat = [$input];
    }

    foreach ($flat as $inputFileName) {
        $baseInputFilename = basename($inputFileName);
        if(!str_starts_with($baseInputFilename, $startWith)){
            continue;
        }

        if (!preg_match('/(\w+)\.json$/', $inputFileName, $matches)) {
            if($isDirectory){
                // Skip error message if the original input was a directory.
                continue;
            }
            return error("Invalid file name for $inputFileName, please make sure each file name has the `.json` extension and the name is a valid php class name.");
        } 
        $className = $matches[1];
        $output    = preg_replace('/\.json$/', '.php', $inputFileName);
        $content   = jsonToPhp(inputFileName:$inputFileName, className:$className)->try($error);
        if ($error) {
            return error($error);
        }
    
        echo "Parsing json from $inputFileName...\n";


        $file = File::open($output, 'w+')->try($error);
        if ($error) {
            return error($error);
        }
    
        echo "Writing definitions to $output...\n";
    
        $file->write("<?php\n$content")->await()->try($error);
        if ($error) {
            return error($error);
        }
    }


    echo "Done.\n";

    return ok();
}


/**
 * 
 * @param  string         $inputFileName
 * @param  string         $className
 * @return Unsafe<string>
 */
function jsonToPhp(string $inputFileName, string $className):Unsafe {
    $json = File::readJson(stdClass::class, $inputFileName)->try($error);
    if ($error) {
        return error($error);
    }

    $customProperties = findCustomPropertiesByJson(prefix:'', json:$json);

    /** @var array<CustomClassProperty> */
    $nestedCustomProperties = [];

    foreach ($customProperties as $customProperty) {
        if ($customProperty instanceof CustomClassProperty) {
            $subClassesLocal        = CustomClassProperty::findNestedCustomPropertiesByCustomProperty($customProperty);
            $nestedCustomProperties = [...$nestedCustomProperties, ...$subClassesLocal];
        }
    }

    $allCustomProperties = [...$customProperties, ...$nestedCustomProperties];

    $result = '';

    foreach ($allCustomProperties as $customClassProperty) {
        if ($customClassProperty instanceof CustomPrimitiveProperty) {
            continue;
        }
        $result .= $customClassProperty->getDefinition().PHP_EOL.PHP_EOL;
    }

    $main   = CustomClassProperty::create(prefix:'', type:$className, name:'root', properties:$customProperties);
    $result = $main->getDefinition().PHP_EOL.PHP_EOL.$result;
    
    return ok($result);
}

/**
 * 
 * @param  string                                                          $prefix Prefix used for property name a class name.
 * @param  string                                                          $name   Name to assign to the property
 * @param  mixed                                                           $json   Json to evaluate.
 * @param  int                                                             $nested How deep the given `$json` is nested an array.
 * @return CustomPrimitiveProperty|CustomClassProperty|CustomArrayProperty
 */
function findCustomPropertyByJson(
    string $prefix,
    string $name,
    mixed $json,
    int $nested = 0,
):CustomPrimitiveProperty|CustomClassProperty|CustomArrayProperty {
    if (is_array($json)) {
        $firstItem      = $json[0];
        $customProperty = findCustomPropertyByJson(
            prefix:$prefix,
            json:$firstItem,
            name:$name,
        );
        while ($customProperty instanceof CustomArrayProperty) {
            $nested++;
            $customProperty = $customProperty->item;
        }
        return CustomArrayProperty::create(item:$customProperty, nested: ++$nested);
    } else if (is_object($json)) {
        return CustomClassProperty::create(
            prefix:$prefix,
            type:stringSnakeToPascal($name),
            name:stringSnakeToCamel($name),
            properties:findCustomPropertiesByJson(
                prefix:"{$name}_",
                json:$json,
            ),
        );
    } else {
        return match (true) {
            is_string($json) => CustomPrimitiveProperty::create(type:'string', name:stringSnakeToCamel($name)),
            is_bool($json)   => CustomPrimitiveProperty::create(type:'bool', name:stringSnakeToCamel($name)),
            is_int($json)    => CustomPrimitiveProperty::create(type:'int', name:stringSnakeToCamel($name)),
            is_float($json)  => CustomPrimitiveProperty::create(type:'float', name:stringSnakeToCamel($name)),
            default          => CustomPrimitiveProperty::create(type:'mixed', name:stringSnakeToCamel($name)),
        };
    }
}

/**
 * 
 * @param  object                                                                        $json
 * @param  array                                                                         $classes
 * @return array<string,CustomPrimitiveProperty|CustomClassProperty|CustomArrayProperty>
 */
function findCustomPropertiesByJson(
    string $prefix,
    object $json,
):array {
    /** @var array<string,CustomPrimitiveProperty|CustomClassProperty|CustomArrayProperty> */
    $properties = [];
    foreach ($json as $key => $value) {
        $name = preg_replace('/\W/', '_', strtolower($key));
        // $prefixedPropertyName      = preg_replace('/\W/', '_', strtolower("$prefix$key"));
        $properties[$name] = findCustomPropertyByJson(
            json:$value,
            name:$name,
            prefix:$prefix,
        );
    }
    return $properties;
}
