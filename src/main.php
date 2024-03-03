<?php

use function Amp\File\isDirectory;
use CatPaw\Core\Attributes\Arguments;
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
function main(#[Arguments] array $arguments = []):Unsafe {
    if (count($arguments) < 1) {
        return error("Input argument is required, for example `php jtp.phar Payload.json`.");
    }
    
    [$input] = $arguments;


    if (isDirectory($input)) {
        $flat = Directory::flat($input)->try($error);
        if ($error) {
            return error($error);
        }
    } else {
        $flat = [$input];
    }

    foreach ($flat as $inputFileName) {
        if (!preg_match('/(\w+)\.json$/', $inputFileName, $matches)) {
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

    $customProperties = createCustomPropertiesFromJson(prefix:'', json:$json);

    /** @var array<CustomClassProperty> */
    $nestedCustomProperties = [];

    foreach ($customProperties as $customProperty) {
        if ($customProperty instanceof CustomClassProperty) {
            $subClassesLocal        = CustomClassProperty::findNestedCustomProperties($customProperty);
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

    $main   = CustomClassProperty::create($className, 'root', $customProperties);
    $result = $main->getDefinition().PHP_EOL.PHP_EOL.$result;
    

    return ok($result);
}


function nestCustomArrayPropertiesFromJson(
    mixed $json,
    string $propertyName,
    string $prefixedPropertyName,
    int $nested = 0,
):CustomArrayProperty {
    $customProperty = createCustomPropertyFromJson(
        json: $json,
        propertyName: $propertyName,
        prefixedPropertyName: $prefixedPropertyName,
    );
    while ($customProperty instanceof CustomArrayProperty) {
        $nested++;
        $customProperty = $customProperty->item;
    }
    return CustomArrayProperty::create(item: $customProperty, nested: ++$nested);
}

function createCustomPropertyFromJson(
    mixed $json,
    string $propertyName,
    string $prefixedPropertyName,
    int $nested = 0,
):CustomPrimitiveProperty|CustomClassProperty|CustomArrayProperty {
    if (is_array($json)) {
        return nestCustomArrayPropertiesFromJson(
            json:$json[0],
            prefixedPropertyName:$prefixedPropertyName,
            propertyName:$propertyName,
            nested:$nested,
        );
    } else if (is_object($json)) {
        return CustomClassProperty::create(
            className:convertSnakeToPascal($prefixedPropertyName),
            propertyName:convertSnakeToCamel($propertyName),
            properties:createCustomPropertiesFromJson(
                prefix:"{$propertyName}_",
                json:$json,
            ),
        );
    } else {
        return match (true) {
            is_string($json) => CustomPrimitiveProperty::create(className:'string', propertyName:convertSnakeToCamel($propertyName)),
            is_bool($json)   => CustomPrimitiveProperty::create(className:'bool', propertyName:convertSnakeToCamel($propertyName)),
            is_int($json)    => CustomPrimitiveProperty::create(className:'int', propertyName:convertSnakeToCamel($propertyName)),
            is_float($json)  => CustomPrimitiveProperty::create(className:'float', propertyName:convertSnakeToCamel($propertyName)),
            default          => CustomPrimitiveProperty::create(className:'mixed', propertyName:convertSnakeToCamel($propertyName)),
        };
    }
}

/**
 * 
 * @param  object                                                                        $json
 * @param  array                                                                         $classes
 * @return array<string,CustomPrimitiveProperty|CustomClassProperty|CustomArrayProperty>
 */
function createCustomPropertiesFromJson(
    string $prefix,
    object $json,
):array {
    /** @var array<string,CustomPrimitiveProperty|CustomClassProperty|CustomArrayProperty> */
    $properties = [];
    foreach ($json as $key => $value) {
        $propertyName              = preg_replace('/\W/', '_', strtolower($key));
        $prefixedPropertyName      = preg_replace('/\W/', '_', strtolower("$prefix$key"));
        $properties[$propertyName] = createCustomPropertyFromJson(
            json: $value,
            propertyName: $propertyName,
            prefixedPropertyName: $prefixedPropertyName,
        );
    }
    return $properties;
}

function convertSnakeToPascal(string $value) {
    return ucfirst(convertSnakeToCamel($value));
}

function convertSnakeToCamel(string $value) {
    $result    = '';
    $upperNext = false;
    foreach (str_split($value) as $char) {
        if ('_' === $char) {
            $upperNext = true;
            continue;
        }

        if ($upperNext) {
            $result .= strtoupper($char);
            $upperNext = false;
        } else {
            $result .= $char;
        }
    }
    return $result;
}

readonly class CustomClassProperty {
    /**
     * 
     * @param  CustomClassProperty        $customClassProperty
     * @return array<CustomClassProperty>
     */
    public static function findNestedCustomProperties(CustomClassProperty $customClassProperty):array {
        $customProperties = [];
        foreach ($customClassProperty->properties as $property) {
            if ($property instanceof CustomArrayProperty) {
                $property = $property->item;
            }

            if ($property instanceof CustomClassProperty) {
                $customProperties[$property->propertyName] = $property;
            }
        }
        return $customProperties;
    }

    /**
     * 
     * @param  string                                             $className
     * @param  string                                             $propertyName
     * @param  array<CustomPrimitiveProperty|CustomClassProperty> $properties
     * @return CustomClassProperty
     */
    public static function create(
        string $className,
        string $propertyName,
        array $properties,
    ):self {
        return new self(className:$className, propertyName:$propertyName, properties: $properties);
    }
        
    /**
     * 
     * @param  string                                                                 $className
     * @param  string                                                                 $propertyName
     * @param  array<CustomPrimitiveProperty|CustomClassProperty|CustomArrayProperty> $properties
     * @return void
     */
    private function __construct(
        public string $className,
        public string $propertyName,
        public array $properties,
    ) {
    }

    public function getDefinition(): string {
        $padding4  = str_repeat(' ', 4);
        $padding8  = str_repeat(' ', 8);
        $padding12 = str_repeat(' ', 12);

        // For annotation
        $stringifiedAnnotationEntries = "";
        foreach ($this->properties as $property) {
            $stringifiedAnnotationEntries .= $padding4.$property->toStringForAnnotation().PHP_EOL;
        }
        $stringifiedAnnotationEntries = trim($stringifiedAnnotationEntries);

        // For create
        // $stringifiedCreateProperties = "";
        // foreach ($this->properties as $property) {
        //     $stringifiedCreateProperties .= $padding8.$property->toStringForCreate().PHP_EOL;
        // }
        // $stringifiedCreateProperties = trim($stringifiedCreateProperties);

        // For new self
        $stringifiedNewSelfParameters = "";
        foreach ($this->properties as $property) {
            if ($property instanceof CustomArrayProperty) {
                $stringifiedNewSelfParameters .= "{$padding12}{$property->item->propertyName}:\${$property->item->propertyName},\n";
            } else {
                $stringifiedNewSelfParameters .= "{$padding12}{$property->propertyName}:\${$property->propertyName},\n";
            }
        }
        $stringifiedNewSelfParameters = trim($stringifiedNewSelfParameters);

        // For __construct
        $stringifiedConstructorProperties = "";
        foreach ($this->properties as $property) {
            $stringifiedConstructorProperties .= $padding8.$property->toStringForConstructor().PHP_EOL;
        }
        $stringifiedConstructorProperties = trim($stringifiedConstructorProperties);

        $className = convertSnakeToPascal($this->className);

        // return <<<PHP
        //     class {$className} {
        //         /**
        //          $stringifiedAnnotationEntries
        //          */
        //         public static function create(
        //             $stringifiedCreateProperties
        //         ):self {
        //             return new self(
        //                 $stringifiedNewSelfParameters
        //             );
        //         }

        //         /**
        //          $stringifiedAnnotationEntries
        //          */
        //         private function __construct(
        //             $stringifiedConstructorProperties
        //         ){}
        //     }
        //     PHP;
        return <<<PHP
            class {$className} {
                /**
                 $stringifiedAnnotationEntries
                 */
                private function __construct(
                    $stringifiedConstructorProperties
                ){}
            }
            PHP;
    }

    public function toStringForAnnotation() {
        return <<<PHP
             * @var {$this->className} \${$this->propertyName}
            PHP;
    }

    public function toStringForCreate(): string {
        return <<<PHP
            {$this->className} \${$this->propertyName},
            PHP;
    }

    public function toStringForConstructor(): string {
        return <<<PHP
            public {$this->className} \${$this->propertyName},
            PHP;
    }
}

readonly class CustomPrimitiveProperty {
    public static function create(
        string $className,
        string $propertyName,
    ):self {
        return new self(
            className:$className,
            propertyName:$propertyName,
        );
    }
    private function __construct(
        public string $className,
        public string $propertyName,
    ) {
    }

    public function toStringForAnnotation() {
        return <<<PHP
             * @var {$this->className} \${$this->propertyName}
            PHP;
    }

    public function toStringForCreate(): string {
        return <<<PHP
            {$this->className} \${$this->propertyName},
            PHP;
    }

    public function toStringForConstructor(): string {
        return <<<PHP
            public {$this->className} \${$this->propertyName},
            PHP;
    }
}

readonly class CustomArrayProperty {
    public static function create(
        CustomPrimitiveProperty|CustomClassProperty $item,
        int $nested,
    ):self {
        return new self(
            item:$item,
            nested:$nested,
        );
    }

    private function __construct(
        public CustomPrimitiveProperty|CustomClassProperty $item,
        public int $nested,
    ) {
    }

    private static function nest(string $value, int $times) {
        $value = "array<$value>";
        $times--;
        if ($times < 1) {
            return $value;
        }
        return self::nest(
            value:$value,
            times:$times,
        );
    }

    public function toStringForAnnotation() {
        $nestedClassName = self::nest(
            value:$this->item->className,
            times:$this->nested,
        );
        return <<<PHP
             * @var $nestedClassName \${$this->item->propertyName}
            PHP;
    }

    public function toStringForCreate(): string {
        return <<<PHP
            array \${$this->item->propertyName}
            PHP;
    }

    public function toStringForConstructor(): string {
        return <<<PHP
            public array \${$this->item->propertyName}
            PHP;
    }
}