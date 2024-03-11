<?php
namespace App;

function stringSnakeToPascal(string $value) {
    return ucfirst(stringSnakeToCamel($value));
}

function stringSnakeToCamel(string $value) {
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

/**
 * 
 * @param  CustomClassProperty|CustomArrayProperty|CustomPrimitiveProperty $target
 * @return array<CustomClassProperty>
 */
function flattenProperties(CustomClassProperty|CustomArrayProperty|CustomPrimitiveProperty $target):array {
    if ($target instanceof CustomPrimitiveProperty) {
        return [];
    }

    if ($target instanceof CustomArrayProperty) {
        return flattenProperties($target->item);
    }
    
    $customProperties = [];

    foreach ($target->properties as $property) {
        if ($property instanceof CustomArrayProperty) {
            $customProperties = [
                ...$customProperties,
                ...flattenProperties($property),
            ];
        } else if ($property instanceof CustomClassProperty) {
            $customProperties[$property->prefix.$property->name] = $property;
        }
    }
    return $customProperties;
}