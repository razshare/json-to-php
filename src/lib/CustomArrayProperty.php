<?php
namespace App;

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
        if($this->item instanceof CustomClassProperty){
            $typePrefix      = stringSnakeToPascal($this->item->prefix);
        }else {
            $typePrefix = '';
        }
        
        $nestedClassName = self::nest(
            value:$typePrefix.$this->item->type,
            times:$this->nested,
        );
        return <<<PHP
             * @var $nestedClassName \${$this->item->name}
            PHP;
    }

    public function toStringForCreate(): string {
        return <<<PHP
            array \${$this->item->name},
            PHP;
    }

    public function toStringForConstructor(): string {
        return <<<PHP
            public array \${$this->item->name},
            PHP;
    }
}