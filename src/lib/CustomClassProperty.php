<?php
namespace App;

readonly class CustomClassProperty {
    /**
     * 
     * @param  CustomClassProperty        $customClassProperty
     * @return array<CustomClassProperty>
     */
    public static function findNestedCustomPropertiesByCustomProperty(CustomClassProperty $customClassProperty):array {
        $customProperties = [];
        foreach ($customClassProperty->properties as $property) {
            if ($property instanceof CustomArrayProperty) {
                $property = $property->item;
            }

            if ($property instanceof CustomClassProperty) {
                $customProperties[$property->name] = $property;
            }
        }
        return $customProperties;
    }

    /**
     * 
     * @param  string                                             $prefix Prefix to add to the type and name.
     * @param  string                                             $type Type of the property.
     * @param  string                                             $name Name of the property.
     * @param  array<CustomPrimitiveProperty|CustomClassProperty> $properties
     * @return CustomClassProperty
     */
    public static function create(
        string $prefix,
        string $type,
        string $name,
        array $properties,
    ):self {
        return new self(
            prefix:$prefix,
            type:$type,
            name:$name,
            properties: $properties,
        );
    }
        
    /**
     * 
     * @param  string                                                                 $prefix
     * @param  string                                                                 $type
     * @param  string                                                                 $name
     * @param  array<CustomPrimitiveProperty|CustomClassProperty|CustomArrayProperty> $properties
     * @return void
     */
    private function __construct(
        public string $prefix,
        public string $type,
        public string $name,
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
                $namePrefix = stringSnakeToCamel($property->item->name);
                $stringifiedNewSelfParameters .= "{$padding12}$namePrefix{$property->item->name}:\$$namePrefix{$property->item->name},\n";
            } else {
                $namePrefix = stringSnakeToCamel($property->name);
                $stringifiedNewSelfParameters .= "{$padding12}$namePrefix{$property->name}:\$$namePrefix{$property->name},\n";
            }
        }
        $stringifiedNewSelfParameters = trim($stringifiedNewSelfParameters);

        // For __construct
        $stringifiedConstructorProperties = "";
        foreach ($this->properties as $property) {
            $stringifiedConstructorProperties .= $padding8.$property->toStringForConstructor().PHP_EOL;
        }
        $stringifiedConstructorProperties = trim($stringifiedConstructorProperties);

        $typePrefix = stringSnakeToPascal($this->prefix);

        $type = stringSnakeToPascal($this->type);

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
            class $typePrefix{$type} {
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
        $typePrefix = stringSnakeToPascal($this->prefix);
        return <<<PHP
             * @var $typePrefix{$this->type} \${$this->name}
            PHP;
    }

    public function toStringForCreate(): string {
        $typePrefix = stringSnakeToPascal($this->prefix);
        return <<<PHP
            $typePrefix{$this->type} \${$this->name},
            PHP;
    }

    public function toStringForConstructor(): string {
        $typePrefix = stringSnakeToPascal($this->prefix);
        return <<<PHP
            public $typePrefix{$this->type} \${$this->name},
            PHP;
    }
}